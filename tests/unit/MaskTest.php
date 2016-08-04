<?php

use WebChemistry\Test\Services;

class MaskTest extends \PHPUnit_Framework_TestCase {

	use \WebChemistry\Test\TMethods;

	/** @var \WebChemistry\Test\Components\Form */
	private $forms;

	protected function setUp() {
		$this->forms = $forms = Services::forms(TRUE);
		$forms->addForm('form', function () {
			$form = new \Form;

			$form->addMask('mask')
				->setMask('999 aaa');

			$form->addMask('regex')
				->setRegex('[0-9]{3} [a-z]{3}');

			return $form;
		});

		$forms->addForm('required', function () {
			$form = new \Form;

			$form->addMask('mask')
				->setRequired()
				->setMask('999 aaa');

			$form->addMask('regex')
				->setRequired()
				->setRegex('[0-9]{3} [a-z]{3}');

			return $form;
		});
	}

	public function failure(\Exception $e) {
		$this->fail($e->getMessage());
	}

	protected function tearDown() {
	}

	public function testMask() {
		$form = new Form;

		$mask = $form->addMask('mask');

		$mask->setMask('999 *** aaa');
		$mask->getControl();

		$rules = $mask->getRules()->getIterator();
		$branch = $rules[0]->branch->getIterator();
		$this->assertSame('[0-9][0-9][0-9] [0-9a-zA-Z][0-9a-zA-Z][0-9a-zA-Z] [a-zA-Z][a-zA-Z][a-zA-Z]', $branch[0]->arg);

		$this->assertThrowException(function () use ($mask) {
			$mask->setMask('999');
		}, 'Exception');

		$this->assertThrowException(function () use ($mask) {
			$mask->setRegex('999');
		}, 'Exception');
	}

	public function testRegex() {
		$form = new Form;

		$mask = $form->addMask('mask');

		$mask->setRegex('[a-z]{5}[0-9]?(a|b)+');
		$mask->getControl();

		$rules = $mask->getRules()->getIterator();
		$branch = $rules[0]->branch->getIterator();
		$this->assertSame('[a-z]{5}[0-9]?(a|b)+', $branch[0]->arg);
	}

	public function testS() {
		$forms = Services::forms();
		$forms->addForm('my', function () {
			$form = new \Form;

			$form->addMask('mask')
				->setMask('999 aaa');

			$form->addMask('regex')
				->setRegex('[0-9]{3} [a-z]{3}');

			return $form;
		});

		$response = $forms->createRequest('my', [
			'mask' => 'xsda'
		]);
		$form = $response->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertSame('xsda', $form['mask']->getValue());
	}

	public function testSubmitInvalid() {
		$result = $this->forms->createRequest('form', [
			'mask' => 'xsda',
			'regex' => 'asd546'
		]);
		$form = $result->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertTrue($form->hasErrors());
		$this->assertSame(array(
			0 => 'Please enter a value in the required format.'
		), $form['mask']->getErrors());
		$this->assertSame(array(
			0 => 'Please enter a value in the required format.'
		), $form['regex']->getErrors());
	}

	public function testSubmit() {
		$result = $this->forms->createRequest('form', [
			'mask' => '124 asd',
			'regex' => '235 wes'
		]);
		$form = $result->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(array(
			'mask' => '124 asd',
			'regex' => '235 wes'
		), $form->getValues(TRUE));
	}

	public function testNotRequired() {
		$result = $this->forms->createRequest('form');
		$form = $result->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
	}

	public function testRequired() {
		$result = $this->forms->createRequest('required');
		$form = $result->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertTrue($form->hasErrors());
	}

}
