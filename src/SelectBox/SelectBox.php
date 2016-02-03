<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\ChoiceControl;
use Nette\Forms\Helpers;
use Nette\Utils\Arrays;
use Nette\Utils\Html;

class SelectBox extends \Nette\Forms\Controls\SelectBox {

	/** @var bool */
	private $translate = TRUE;

	/** @var array of option / optgroup */
	private $options = [];

	/**
	 * Sets options and option groups from which to choose.
	 * @return self
	 */
	public function setItems(array $items, $useKeys = TRUE) {
		if (!$useKeys) {
			$res = array();
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

		return ChoiceControl::setItems(Arrays::flatten($items, TRUE));
	}

	/**
	 * Generates control's HTML element.
	 *
	 * @return Html
	 */
	public function getControl() {
		$items = $this->getPrompt() === FALSE ? [] : ['' => $this->translate($this->getPrompt())];
		foreach ($this->options as $key => $value) {
			$items[$this->translate && is_array($value) ? $this->translate($key) : $key] =
				$this->translate ? $this->translate($value) : $value;
		}

		return Helpers::createSelectBox(
			$items,
			[
				'selected?' => $this->value,
				'disabled:' => is_array($this->disabled) ? $this->disabled : NULL,
			]
		)->addAttributes(BaseControl::getControl()->attrs);
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
