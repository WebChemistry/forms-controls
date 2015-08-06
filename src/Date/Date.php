<?php

namespace WebChemistry\Forms\Controls;

use Nette;
use Nette\Utils\Html;

class Date extends Nette\Forms\Controls\BaseControl {

	const TIMESTAMP = 'timestamp';
	const DATETIME = 'datetime';

	protected $format = array(
		'D' => 'day', 'M' => 'month', 'Y' => 'year', 'h' => 'hours', 'm' => 'minutes', 's' => 'seconds'
	);

	protected $dateFormat = array(
		'day' => 'j', 'month' => 'n', 'year' => 'Y', 'hours' => 'G', 'minutes' => 'i', 'seconds' => 's'
	);

	protected $separators = array();

	protected $fields = array(
		'day', 'month', 'year', 'hours', 'minutes'
	);

	protected $values = array(
		'day' => NULL, 'month' => NULL, 'year' => NULL, 'hours' => NULL, 'minutes' => NULL, 'seconds' => NULL
	);

	protected $range = array(
		'day' => array(1, 31, 'mday'), 'month' => array(1, 12, 'mon'), 'year' => array(1900, 2038), 'hours' => array(0, 23),
		'minutes' => array(0, 60), 'seconds' => array(0, 60)
	);

	/** @var array */
	protected $settings = array();

	/** @var mixed */
	protected $rawValue;

	/** @var string */
	protected $type = self::DATETIME;

	/**
	 * @param string   $caption
	 * @param string $format
	 * @throws \Exception
	 */
	public function __construct($caption = NULL, $format = 'D.M.Y h:m') {
		parent::__construct($caption);

		$this->setFormat($format);
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
	 * @return bool
	 */
	private function checkValues() {
		$isJs = TRUE;

		foreach ($this->values as $value) {
			if ($value !== NULL) {
				$isJs = FALSE;
			}
		}

		return $isJs;
	}

	public function loadHttpData() {
		foreach ($this->fields as $name) {
			$this->values[$name] = $this->getHttpData(Nette\Forms\Form::DATA_LINE, '[' . $name . ']');
		}

		$this->rawValue = $this->values;

		if ($this->checkValues()) {
			$this->rawValue = $this->getHttpData(Nette\Forms\Form::DATA_LINE, '[js]');
		}

		$this->setValue($this->getDate());
	}

	/**
	 * Returns control's value.
	 *
	 * @return int|\DateTime
	 */
	public function getValue() {
		if ($this->type === self::DATETIME) {
			return parent::getValue();
		} else {
			return parent::getValue()->getTimestamp();
		}
	}

	/**
	 * @return array
	 */
	public function getRawValue() {
		return $this->rawValue;
	}

	/**
	 * @return string
	 */
	public function getJsFormat() {
		$return = '';

		foreach ($this->fields as $name) {
			$return .= $this->dateFormat[$name] . $this->getSeparator($name);
		}

		return $return;
	}

	/**
	 * Sets control's value.
	 *
	 * @return self
	 */
	public function setValue($value) {
		if (!$value instanceof \DateTime) {
			$value = Nette\Utils\DateTime::from($value);
		}

		return parent::setValue($value);
	}

	/**
	 * @return Html
	 */
	public function getControl() {
		$controlName = $this->getHtmlName();
		$date = $this->value ? $this->value: new \DateTime;
		$html = Html::el();

		$input = Html::el('input')->name($controlName . '[js]')->id($this->getHtmlId());
		$input->class[] = 'js';
		$input->class[] = 'date-input';
		$input->data('format', $this->getJsFormat());
		$input->data('settings', $this->settings);
		$input->value($date->format($this->getJsFormat()));

		$html->add($input);

		$container = Html::el('div');
		$container->class[] = 'no-js date-input-container';

		foreach ($this->fields as $name) {
			$rangeValues = $this->range[$name];
			$range = range($rangeValues[0], $rangeValues[1]);
			$fieldName = isset($rangeValues[2]) ? $rangeValues[2] : $name;

			$date = getdate($this->value ? $this->value->getTimestamp() : time());

			$input = Nette\Forms\Helpers::createSelectBox(
					array_combine($range, $range),
					array('selected?' => $date[$fieldName])
				)->name($controlName . '[' . $name . ']');

			$input->class[] = 'no-js';

			$container->add($input . $this->getSeparator($name));
		}

		$html->add($container);

		return $html;
	}

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getSeparator($name) {
		if (!array_key_exists($name, $this->separators)) {
			return NULL;
		}

		return $this->separators[$name];
	}

	/**
	 * @param string $format
	 * @return $this
	 */
	public function setFormat($format) {
		$this->fields = array();
		$this->separators = array();

		preg_match_all('#([DMYhms]{1})([^DMYhms]+)?#', $format, $matches);

		foreach ($matches[1] as $key => $value) {
			$value = trim($value);

			if (!array_key_exists($value, $this->format)) {
				throw new \Exception("DateInput: Bad format for '$value'");
			}

			$this->fields[] = $this->format[$value];
			$this->separators[$this->format[$value]] = $matches[2][$key];
		}

		return $this;
	}

	/**
	 * @param $value
	 */
	protected function getDate() {
		$values = $this->rawValue;

		$str = '';
		$format = '';

		if (is_array($values)) {
			foreach ($values as $name => $value) {
				if ($value === NULL && array_search($name, $this->fields) === FALSE) {
					continue;
				}

				$format .= $this->dateFormat[$name] . '.';
				if (in_array($name, array(
					'minutes',
					'seconds'
				))) {
					$str .= sprintf("%02d", $value) . '.';
				} else {
					$str .= $value . '.';
				}
			}

			$str = substr($str, 0, -1);
			$format = substr($format, 0, -1);
		} else {
			$format = $this->getJsFormat();
			$str = $values;
		}

		$date = \DateTime::createFromFormat($format, $str);

		if (!$date || $date->format($format) !== $str) {
			$this->addError('Date is invalid.');
		}

		return \DateTime::createFromFormat($format, $str);
	}
}
