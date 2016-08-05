<?php

use WebChemistry\Test\Services;

class RecaptchaTest extends \PHPUnit_Framework_TestCase {

	use \WebChemistry\Test\TMethods;

	/** @var \WebChemistry\Test\Components\Form */
	private $forms;

	protected function setUp() {
		$this->forms = $forms = Services::forms(TRUE);

		$forms->addForm('form', function () {
			$form = new \Form();

			$form['recaptcha'] = $recaptcha = new \WebChemistry\Forms\Controls\Recaptcha();

			$recaptcha->setApiKey('api');
			$recaptcha->setSecretKey('secret');

			return $form;
		});
		$forms->addForm('withoutKeys', function () {
			$form = new \Form();

			$form['recaptcha'] = $recaptcha = new \WebChemistry\Forms\Controls\Recaptcha();

			return $form;
		});
	}

	protected function tearDown() {
	}

	public function testControl() {
		$form = new Form;

		$recaptcha = $form->addRecaptcha('recaptcha');
		$recaptcha->setApiKey('api');
		$recaptcha->setSecretKey('secret');
		$this->assertSame('api', $recaptcha->getApiKey());

		$this->assertNotEmpty($recaptcha->getControl());
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

	public function testSubmitWithoutKeys() {
		$this->assertThrowException(function () {
			$this->forms->createRequest('withoutKeys', [
				'g-recaptcha-response' => '48sf8sagd48gas48as84asf'
			]);
		}, 'WebChemistry\Forms\ControlException');
	}

}
