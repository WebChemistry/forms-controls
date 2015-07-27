<?php

use Tester\Assert as A;

class MaskTest extends \PHPUnit_Framework_TestCase {

	/** @var \Nette\Application\IPresenterFactory */
	private $presenterFactory;

	protected function setUp() {
		$this->presenterFactory = E::getByType('Nette\Application\IPresenterFactory');

		A::$onFailure = array($this, 'failure');
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
		$this->assertSame('[0-9][0-9][0-9] [0-9a-zA-Z][0-9a-zA-Z][0-9a-zA-Z] [a-zA-Z][a-zA-Z][a-zA-Z]', $rules[0]->arg);

		A::throws(function () use ($mask) {
			$mask->setMask('999');
		}, 'Exception');

		A::throws(function () use ($mask) {
			$mask->setRegex('999');
		}, 'Exception');
	}

	public function testRegex() {
		$form = new Form;

		$mask = $form->addMask('mask');

		$mask->setRegex('[a-z]{5}[0-9]?(a|b)+');
		$mask->getControl();

		$rules = $mask->getRules()->getIterator();
		$this->assertSame('[a-z]{5}[0-9]?(a|b)+', $rules[0]->arg);
	}

	public function testSubmitInvalid() {
		$presenter = $this->presenterFactory->createPresenter('Mask');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Mask', 'POST', array(
			'do' => 'form-submit'
		), array(
			'mask' => 'xsda',
			'regex' => 'asd546'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

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
		$presenter = $this->presenterFactory->createPresenter('Mask');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Mask', 'POST', array(
			'do' => 'form-submit'
		), array(
			'mask' => '124 asd',
			'regex' => '235 wes'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(array(
			'mask' => '124 asd',
			'regex' => '235 wes'
		), $form->getValues(TRUE));
	}
}
