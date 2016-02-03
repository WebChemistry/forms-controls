<?php

class DateTest extends \PHPUnit_Framework_TestCase {

	/** @var \Nette\Application\IPresenterFactory */
	private $presenterFactory;

	protected function setUp() {
		$this->presenterFactory = E::getByType('Nette\Application\IPresenterFactory');
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
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => '27.07.2015 14:00'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(strtotime('27.07.2015 14:00'), $form->values['date']);

		// Filled seconds
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => '27.07.2015 14:00:45'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertTrue($form->hasErrors());
		$this->assertSame(sprintf('Date is not in expected format (example of correct date: %s).', date('j.m.Y H:i', time())), $form->errors[0]);

		// Invalid
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => '27.07.2015 14:00'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
	}
}
