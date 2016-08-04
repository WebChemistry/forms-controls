<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\MultiChoiceControl;
use Nette\Forms\Helpers;
use Nette\Utils\Arrays;
use Nette\Utils\Html;

class MultiSelectBox extends MultiChoiceControl {

	/** @var bool */
	private $translate = TRUE;

	/** @var array of option / optgroup */
	private $options = [];


	/**
	 * Sets options and option groups from which to choose.
	 *
	 * @return self
	 */
	public function setItems(array $items, $useKeys = TRUE) {
		if (!$useKeys) {
			$res = [];
			foreach ($items as $key => $value) {
				unset($items[$key]);
				if (is_array($value)) {
					foreach ($value as $val) {
						$res[$key][(string) $val] = $val;
					}
				} else {
					$res[(string) $value] = $value;
				}
			}
			$items = $res;
		}
		$this->options = $items;

		return parent::setItems(Arrays::flatten($items, TRUE));
	}

	/**
	 * Generates control's HTML element.
	 *
	 * @return Html
	 */
	public function getControl() {
		$items = [];
		foreach ($this->options as $key => $value) {
			$items[is_array($value) && $this->translate ? $this->translate($key) : $key] =
				$this->translate ? $this->translate($value) : $value;
		}

		return Helpers::createSelectBox(
			$items,
			[
				'selected?' => $this->value,
				'disabled:' => is_array($this->disabled) ? $this->disabled : NULL,
			]
		)->addAttributes(parent::getControl()->attrs)->multiple(TRUE);
	}

	/**
	 * @param bool $translate
	 * @return self
	 */
	public function setTranslate($translate) {
		$this->translate = (bool) $translate;

		return $this;
	}

}
