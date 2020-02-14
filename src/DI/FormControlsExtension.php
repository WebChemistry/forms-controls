<?php declare(strict_types=1);

namespace WebChemistry\Controls\DI;

use Nette\DI\CompilerExtension;
use Nette\Forms\Validator;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use WebChemistry\Controls\DateInput;

class FormControlsExtension extends CompilerExtension {

	public function getConfigSchema(): Schema {
		return Expect::structure([
			'translations' => Expect::structure([
				'date' => Expect::string('Date is not in correct format'),
			]),
			'dateFormats' => Expect::structure([
				'date' => Expect::string(DateInput::$dateFormats[DateInput::DATE]),
				'datetime' => Expect::string(DateInput::$dateFormats[DateInput::DATETIME]),
				'month' => Expect::string(DateInput::$dateFormats[DateInput::MONTH]),
				'week' => Expect::string(DateInput::$dateFormats[DateInput::WEEK]),
			])
		]);
	}

	public function afterCompile(ClassType $class) {
		$init = $class->getMethods()['initialize'];
		$config = $this->getConfig();

		$init->addBody(Validator::class . '::$messages[?] = ?;', [DateInput::DATE_VALID, $config->translations->date]);

		$init->addBody(DateInput::class . '::$dateFormats[?] = ?;', [DateInput::DATE, $config->dateFormats->date]);
		$init->addBody(DateInput::class . '::$dateFormats[?] = ?;', [DateInput::DATETIME, $config->dateFormats->datetime]);
		$init->addBody(DateInput::class . '::$dateFormats[?] = ?;', [DateInput::MONTH, $config->dateFormats->month]);
		$init->addBody(DateInput::class . '::$dateFormats[?] = ?;', [DateInput::WEEK, $config->dateFormats->week]);
	}

}
