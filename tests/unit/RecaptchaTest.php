<?php

class RecaptchaTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {

	}

	protected function tearDown() {
	}

	public function testControl() {
		$form = new Form;

		$form->setRecaptchaConfig([
			'secret' => 'a1evt88av18avte',
			'api' => 'aev464vaew8vaet8'
		]);
		$recaptcha = $form->addRecaptcha('recaptcha');
		$this->assertSame('aev464vaew8vaet8', $recaptcha->getApiKey());

		$this->assertStringEqualsFile(E::dumpedFile('recaptcha'), $recaptcha->getControl());
	}

	public function testSubmit() {
		$presenterFactory = E::getByType('Nette\Application\IPresenterFactory');

		/** @var \App\Presenters\RecaptchaPresenter $presenter */
		$presenter = $presenterFactory->createPresenter('Recaptcha');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Recaptcha', 'POST', array(
			'do' => 'form-submit'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertFalse($form->isValid());
		$this->assertSame(array(
			0 => 'Please fill antispam.'
		), $form->getErrors());

		/** @var \App\Presenters\RecaptchaPresenter $presenter */
		$presenter = $presenterFactory->createPresenter('Recaptcha');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('Recaptcha', 'POST', array(
			'do' => 'form-submit'
		), array(
			'g-recaptcha-response' => '48sf8sagd48gas48as84asf'
		)));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertFalse($form->isValid());
		$this->assertSame(array(
			0 => 'Antispam detection was not successful.'
		), $form->getErrors());
	}
}
