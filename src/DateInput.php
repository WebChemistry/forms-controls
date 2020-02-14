<?php declare(strict_types=1);

namespace WebChemistry\Controls;

use DateTime;
use Nette;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Validator;

final class DateInput extends TextInput {

	const DATE_VALID = 'wchDate';

	/** @var string[] */
	public static $dateFormats = [
		self::DATE => 'Y-m-d',
		self::DATETIME => 'Y-m-d\TH:i',
		self::MONTH => 'Y-m',
		self::WEEK => 'Y-\WW',
	];

	public const DATE = 0;
	public const DATETIME = 1;
	public const MONTH = 2;
	public const WEEK = 3;

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

	public function setMonth() {
		$this->setHtmlType('month');
		$this->type = self::MONTH;

		return $this;
	}

	public function setWeek() {
		$this->setHtmlType('week');
		$this->type = self::WEEK;

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


		return $this->value->format(self::$dateFormats[$this->type]);
	}

	public function getControl(): Nette\Utils\Html {
		$control = parent::getControl();
		$control->appendAttribute('class', 'js-date-control');

		$control->setAttribute('data-format', self::$dateFormats[$this->type]);

		return $control;
	}

	/**
	 * @param DateTime|null $value
	 */
	public function setValue($value) {
		if ($value !== null && !$value instanceof DateTime) {
			throw new \LogicException("Must be a DateTime or null.");
		}

		$this->value = $value;

		return $this;
	}

	public function getValue(): ?DateTime {
		return $this->value;
	}

}
