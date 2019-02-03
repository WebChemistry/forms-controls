<?php

declare(strict_types=1);

namespace WebChemistry\Controls;

use Nette;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Validator;

final class DateInput extends TextInput {

	const DATE_VALID = 'wchDate';

	private const DATE = 0;
	private const DATETIME = 1;
	private const DATEMONTH = 2;

	/** @var int */
	private $type = self::DATE;

	public function __construct($label = null, int $maxLength = null) {
		parent::__construct($label, $maxLength);

		$this->setHtmlType('date');
	}

	public function setDateTime() {
		$this->setHtmlType('datetime-local');
		$this->type = self::DATETIME;

		return $this;
	}

	public function setDateMonth() {
		$this->setHtmlType('month');
		$this->type = self::DATEMONTH;

		return $this;
	}

	protected function getHttpData($type, ?string $htmlTail = null) {
		$str = parent::getHttpData($type, $htmlTail);


		try {
			$date = null;
			if ($str) {
				$date = new \DateTime($str);
			}
		} catch (\Throwable $e) {
			$msg = Validator::$messages[self::DATE_VALID] ?? 'Date is not correct.';

			$this->addError($msg);

			return null;
		}

		return $date;
	}

	protected function getRenderedValue(): ?string {
		if (!$this->value) {
			return null;
		}

		switch ($this->type) {
			case self::DATETIME:
				return $this->value->format('Y-m-d\TH:i');
			case self::DATE:
				return $this->value->format('Y-m-d');
			default:
				return $this->value->format('Y-m');
		}
	}

	public function getControl(): Nette\Utils\Html {
		$control = parent::getControl();
		if ($this->type === self::DATE) {
			$control->appendAttribute('class', 'date-input date-control');
		} else if ($this->type === self::DATETIME) {
			$control->appendAttribute('class', 'datetime-input date-control');
		} else {
			$control->appendAttribute('class', 'datemonth-input date-control');
		}

		return $control;
	}

	public function setValue($value) {
		if ($value !== null && !$value instanceof \DateTime) {
			throw new \LogicException("Must be a DateTime or null.");
		}

		$this->value = $value;
	}

	public function getValue() {
		return $this->value;
	}

}
