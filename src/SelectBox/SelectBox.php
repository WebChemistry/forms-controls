<?php

namespace WebChemistry\Forms\Controls;

class SelectBox extends \Nette\Forms\Controls\SelectBox {

	/** @var bool */
	private $translate = TRUE;

	/** @var bool */
	private $translateCount = 0;

	/**
	 * Returns translated string.
	 *
	 * @param  mixed
	 * @param  int
	 * @return string
	 */
	public function translate($value, $count = NULL) {
		if ($this->translate) {
			return parent::translate($value, $count);
		}
		if ($this->translateCount >= 1 || $this->prompt === FALSE) {
			return $value;
		}
		$this->translateCount++;

		return parent::translate($value, $count);
	}

	/**
	 * @param $translate
	 * @return self
	 */
	public function setTranslate($translate) {
		$this->translate = (bool) $translate;

		return $this;
	}

}
