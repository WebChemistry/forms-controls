<?php

namespace WebChemistry\Forms\Controls;

class RadioList extends \Nette\Forms\Controls\RadioList {

	/** @var bool */
	private $translate = TRUE;

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

		return $value;
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
