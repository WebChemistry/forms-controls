<?php

class DateTest extends \PHPUnit_Framework_TestCase {

	/** @var \Nette\Application\IPresenterFactory */
	private $presenterFactory;

	protected function setUp() {
		$this->presenterFactory = E::getByType('Nette\Application\IPresenterFactory');
	}

	protected function tearDown() {
	}

	public function testFormat() {
		$form = new Form;

		$date = $form->addDate('date');

		$this->assertSame('j.n.Y G:i', $date->getJsFormat());

		$date = $form->addDate('dates', NULL, 'D  . M : Y h.m- s');

		$this->assertSame('j  . n : Y G.i- s', $date->getJsFormat());
	}

	public function testSeparators() {
		$form = new Form;

		$date = $form->addDate('date', NULL, 'D  . M : Y h.m- s');

		$this->assertSame('', $date->getSeparator('seconds'));
		$this->assertSame('  . ', $date->getSeparator('day'));
		$this->assertSame(' : ', $date->getSeparator('month'));
		$this->assertSame('- ', $date->getSeparator('minutes'));
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

	public function testSubmitJs() {
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => array(
				'js' => '27.7.2015 14:00'
			)
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(strtotime('27.7.2015 14:00'), $form->values['date']);

		// Filled seconds
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => array(
				'js' => '27.07.2015 14:00:45'
			)
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertTrue($form->hasErrors());
		$this->assertSame('Date is invalid.', $form->errors[0]);

		// Invalid
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => array(
				'js' => '27.07.2015 14:00'
			)
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertTrue($form->hasErrors());
		$this->assertSame('Date is invalid.', $form->errors[0]);
	}

	public function testSubmit() {
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => array(
				'js' => '',
				'day' => 27,
				'month' => 7,
				'year' => 2015,
				'hours' => '14',
				'minutes' => '00'
			)
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(strtotime('27.7.2015 14:00'), $form->values['date']);

		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => array(
				'js' => '',
				'day' => 27,
				'month' => 7,
				'year' => 2015,
				'hours' => '14',
				'minutes' => 0
			)
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(strtotime('27.7.2015 14:00'), $form->values['date']);

		// Filled seconds
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => array(
				'js' => '',
				'day' => 27,
				'month' => 7,
				'year' => 2015,
				'hours' => '14',
				'minutes' => 0,
				'seconds' => 45
			)
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(strtotime('27.7.2015 14:00'), $form->values['date']);
	}

	public function testJsAndNormal() {
		$presenter = $this->presenterFactory->createPresenter('Date');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Date', 'POST', array(
			'do' => 'form-submit'
		), array(
			'date' => array(
				'js' => '30.7.2015 14:00',
				'day' => 27,
				'month' => 7,
				'year' => 2015,
				'hours' => '14',
				'minutes' => '00'
			)
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertTrue($form->isSubmitted());
		$this->assertFalse($form->hasErrors());
		$this->assertSame(strtotime('27.7.2015 14:00'), $form->values['date']);
	}
}
