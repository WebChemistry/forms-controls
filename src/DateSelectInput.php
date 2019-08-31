<?php

declare(strict_types=1);

namespace WebChemistry\Controls;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Helpers;
use Nette\Forms\IControl;
use Nette\Utils\DateTime;

final class DateSelectInput extends BaseControl {

	private $month = '';
	private $year = '';

	public function __construct($label = null) {
		parent::__construct($label);
		$this->setRequired(false)->addRule([__CLASS__, 'validateDate'], 'Date is invalid.');
		$this->year = (int) date('Y');
		$this->month = (int) date('n');
	}

	public function setValue($value) {
		if ($value === null) {
			$this->month = $this->year = '';
		} else {
			$date = DateTime::from($value);
			$this->month = (int) $date->format('n');
			$this->year = (int) $date->format('Y');
		}

		return $this;
	}

	/**
	 * @return \DateTimeImmutable|null
	 */
	public function getValue() {
		return self::validateDate($this) ? (new \DateTimeImmutable())->setDate((int) $this->year, (int) $this->month, 1)
			->setTime(0, 0) : null;
	}

	/**
	 * @return bool
	 */
	public function isFilled(): bool {
		return true;
	}

	public function loadHttpData(): void {
		$this->month = $this->getHttpData(Form::DATA_LINE, '[month]');
		$this->year = $this->getHttpData(Form::DATA_LINE, '[year]');
	}

	/**
	 * Generates control's HTML element.
	 */
	public function getControl() {
		$name = $this->getHtmlName();

		return  Helpers::createSelectBox([1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], [], $this->month)
				->name($name . '[month]')
				->setAttribute('class', 'selectInput-month').
				Helpers::createSelectBox(array_combine(range(2016, 2050), range(2016, 2050)), [], $this->year)
				->name($name . '[year]')
				->setAttribute('class', 'selectInput-year');
	}

	/**
	 * @return false|string
	 */
	public function getMonth() {
		return $this->month;
	}

	/**
	 * @return false|string
	 */
	public function getYear() {
		return $this->year;
	}

	/**
	 * @return bool
	 */
	public static function validateDate(IControl $control): bool {
		return ctype_digit((string) $control->month) && ctype_digit((string) $control->year) && checkdate((int) $control->month, 1, (int) $control->year);
	}
}
