<?php

namespace WebChemistry\Forms\Controls;

use Nette;
use Nette\Utils\Html;

class Date extends Nette\Forms\Controls\TextInput {

	const TIMESTAMP = 'timestamp';
	const DATETIME = 'datetime';
	const VALID = ':wchDate';

	/** @var array */
	protected $settings = array();

	/** @var string */
	protected $type = self::DATETIME;

	/** @var string */
	protected $rawValue;

	/** @var string */
	protected $format;

	/** @var string */
	public static $dateFormat = 'Y-m-d H:i';

	/**
	 * @param string   $caption
	 * @param string $format
	 * @throws \Exception
	 */
	public function __construct($caption = NULL, $format = NULL) {
		parent::__construct($caption);

		$this->setFormat($format ? : self::$dateFormat);
	}

	/**
	 * Loads HTTP data.
	 *
	 * @return void
	 */
	public function loadHttpData() {
		$this->rawValue = $this->getHttpData(Nette\Forms\Form::DATA_LINE);

		$this->setValue($this->checkDate());
	}

	/**
	 * @param string $type
	 * @return Date
	 */
	public function setType($type) {
		$this->type = $type;

		return $this;
	}

	/**
	 * Returns control's value.
	 *
	 * @return null|int|\DateTime
	 */
	public function getValue() {
		if ($this->type === self::DATETIME) {
			return $this->value;
		} else {
			return $this->value ? $this->value->getTimestamp() : NULL;
		}
	}

	/**
	 * Sets control's value.
	 *
	 * @return Date
	 */
	public function setValue($value) {
		if ($value instanceof \DateTime) {
			$this->value = $value;
		} else if (is_numeric($value) || is_string($value)) {
			$this->value = Nette\Utils\DateTime::from($value);
		} else {
			$this->value = NULL;
		}
	}

	/**
	 * @return Html
	 */
	public function getControl() {
		$control = parent::getControl();

		$control->class[] = 'js';
		$control->class[] = 'date-input';
		$control->data('format', $this->format);
		$control->data('settings', $this->settings);

		if ($this->value) {
			$control->value($this->value->format($this->format));
		}

		return $control;
	}

	/**
	 * @param string $format
	 * @return Date
	 */
	public function setFormat($format) {
		$this->format = $format;

		return $this;
	}

	/**
	 * @return \DateTime|bool
	 */
	protected function checkDate() {
		if (!$this->isRequired() && !$this->rawValue) {
			return NULL;
		}

		$date = \DateTime::createFromFormat($this->format, $this->rawValue);

		if (!$date || $date->format($this->format) !== $this->rawValue) {
			$message = isset(Nette\Forms\Validator::$messages[self::VALID]) ? Nette\Forms\Validator::$messages[self::VALID] : 'Date is not in expected format (example of correct date: %s).';
			$this->addError(sprintf($message, date($this->format, time())));

			return FALSE;
		}

		return $date;
	}
}