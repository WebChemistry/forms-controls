<?php

use WebChemistry\Test\Services;

class RecaptchaTest extends \PHPUnit_Framework_TestCase {

	/** @var \WebChemistry\Test\Components\Form */
	private $forms;

	protected function setUp() {
		$this->forms = $forms = Services::forms(TRUE);

		$forms->addForm('form', function () {
			$form = new \Form();

			$form['recaptcha'] = new \WebChemistry\Forms\Controls\Recaptcha('a4f45afs45afs', '5ag48ga4gea8aeg');

			return $form;
		});
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

		$this->assertStringEqualsFile(__DIR__ . '/expected/recaptcha.dmp', $recaptcha->getControl());
	}

	public function testSubmit() {
		$result = $this->forms->createRequest('form', [
			'do' => 'form-submit'
		]);

		$form = $result->getForm();

		$this->assertFalse($form->isValid());
		$this->assertSame(array(
			0 => 'Please fill antispam.'
		), $form->getErrors());
	}

	public function testInvalidSubmit() {
		$result = $this->forms->createRequest('form', [
			'do' => 'form-submit',
			'g-recaptcha-response' => '48sf8sagd48gas48as84asf'
		]);

		$form = $result->getForm();

		$this->assertFalse($form->isValid());
		$this->assertSame(array(
			0 => 'Antispam detection was not successful.'
		), $form->getErrors());
	}

}
