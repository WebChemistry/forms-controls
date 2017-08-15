<?php

declare(strict_types=1);

namespace WebChemistry\Controls\DI;

use App\Utils\Validator;
use Nette;
use Nette\DI\CompilerExtension;
use WebChemistry\Forms\Controls\DateInput;

class FormControlsExtension extends CompilerExtension {

	/** @var array */
	public $defaults = [
		'translations' => [
			'date' => 'Date is not correct.',
		]
	];

	public function afterCompile(Nette\PhpGenerator\ClassType $class) {
		$init = $class->getMethods()['initialize'];
		$config = $this->validateConfig($this->defaults)['translations'];

		$init->addBody(Validator::class . '::$messages[?] = ?;', [DateInput::DATE_VALID, $config['date']]);
	}

}
