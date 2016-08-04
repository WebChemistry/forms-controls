<?php

namespace WebChemistry\Forms\DI;

use Nette;
use Nette\DI\CompilerExtension;

class FormControlsExtension extends CompilerExtension {

	/** @var array */
	public $defaults = [
		'recaptcha' => [
			'secret' => NULL,
			'api' => NULL
		],
		'date' => [
			'format' => 'Y-m-d H:i'
		],
		'translations' => [
			'enable' => FALSE,
			'date' => 'Date is not in expected format (example of correct date: %s).',
			'mask' => 'Please enter a value in the required format.',
			'recaptcha' => [
				'filled' => 'Please fill antispam.',
				'valid' => 'Antispam detection was not successful.'
			],
			'tags' => 'For the distribution of words please use comma.'
		]
	];

	public function afterCompile(Nette\PhpGenerator\ClassType $class) {
		$config = $this->validateConfig($this->defaults);
		$init = $class->getMethods()['initialize'];

		if ($config['recaptcha']['api']) {
			$init->addBody('WebChemistry\Forms\Controls\Recaptcha::$defaultApiKey = ?;', [$config['recaptcha']['api']]);
		}
		if ($config['recaptcha']['secret']) {
			$init->addBody('WebChemistry\Forms\Controls\Recaptcha::$defaultSecretKey = ?;', [$config['recaptcha']['secret']]);
		}
		if ($config['date']['format']) {
			$init->addBody('WebChemistry\Forms\Controls\Date::$dateFormat = ?;', [$config['date']['format']]);
		}

		if ($config['translations']['enable']) {
			$tr = $config['translations'];

			$init->addBody('Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Date::VALID] = ?;', [$tr['date']]);
			$init->addBody('Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Mask::VALID] = ?;', [$tr['mask']]);
			$init->addBody('Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Recaptcha::VALID] = ?;', [$tr['recaptcha']['valid']]);
			$init->addBody('Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Recaptcha::FILLED] = ?;', [$tr['recaptcha']['filled']]);
			$init->addBody('Nette\Forms\Validator::$messages[WebChemistry\Forms\Controls\Tags::PLACEHOLDER] = ?;', [$tr['tags']]);
		}
	}

}
