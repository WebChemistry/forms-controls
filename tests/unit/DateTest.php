<?php

class DateTest extends \PHPUnit_Framework_TestCase {

	/** @var \WebChemistry\Test\Components\Form */
	private $forms;

	protected function setUp() {
		$this->forms = $forms = \Webchemistry\Test\Services::forms(TRUE);

		$forms->addForm('form', function () {
			$form = new \Form;

			$form->addDate('date', '', 'j.m.Y H:i')
				->setType(\WebChemistry\Forms\Controls\Date::TIMESTAMP);

			return $form;
		});
		$forms->addForm('defaultDate', function () {
			$form = new \Form;

			$form->addDate('date');

			return $form;
		});
	}

	protected function tearDown() {
	}

	public function testSetValues() {
		$form = new Form;

		$date = $form->addDate('date');

		$date->setType($date::TIMESTAMP);

		$date->setValue(new \DateTime);

		$this->assertSame(time(), $date->getValue());

		$date->setValue(time());

		$this->assertSame(time(), $date->getValue());
	}

	public function testSubmit() {
		$result = $this->forms->createRequest('form', [
			'date' => '27.07.2015 14:00'
		]);
		$form = $result->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(strtotime('27.07.2015 14:00'), $form->values['date']);
	}

	public function testInvalid() {
		$result = $this->forms->createRequest('form', [
			'date' => '27.07.2015 14:00:45'
		]);
		$form = $result->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertTrue($form->hasErrors());
		$this->assertSame(sprintf('Date is not in expected format (example of correct date: %s).', date('j.m.Y H:i', time())), $form->errors[0]);
	}

	public function testSubmitDefaultValue() {
		$result = $this->forms->createRequest('defaultDate', [
			'date' => '27.07.2015 14:00'
		]);
		$form = $result->getForm();

		$this->assertTrue($form->isSubmitted());
		$this->assertTrue($form->hasErrors());
		$this->assertSame(sprintf('Date is not in expected format (example of correct date: %s).', date('Y-m-d H:i', time())), $form->errors[0]);
	}

}
