<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Helpers;
use Nette\Utils\Html;

class SelectBox extends \Nette\Forms\Controls\SelectBox {

	/** @var bool */
	private $translate = TRUE;

	/**
	 * Generates control's HTML element.
	 *
	 * @return Html
	 */
	public function getControl() {
		$items = $this->getPrompt() === FALSE ? [] : ['' => $this->translate($this->getPrompt())];
		foreach ($this->getItems() as $key => $value) {
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
