<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Helpers;

class CheckboxList extends \Nette\Forms\Controls\CheckboxList {

	/** @var bool */
	private $translate = TRUE;

	/**
	 * Generates control's HTML element.
	 *
	 * @return string
	 */
	public function getControl() {
		$items = $this->getItems();
		reset($items);
		$input = BaseControl::getControl();

		return Helpers::createInputList(
			$this->translate ? $this->translate($items) : $items,
			array_merge($input->attrs, [
				'id' => NULL,
				'checked?' => $this->value,
				'disabled:' => $this->disabled,
				'required' => NULL,
				'data-nette-rules:' => [key($items) => $input->attrs['data-nette-rules']],
			]),
			$this->label->attrs,
			$this->separator
		);
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
