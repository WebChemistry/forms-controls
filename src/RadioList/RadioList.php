<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Helpers;
use Nette\Utils\Html;

class RadioList extends \Nette\Forms\Controls\RadioList {

	/** @var bool */
	private $translate = TRUE;

	/**
	 * Generates control's HTML element.
	 *
	 * @return Html
	 */
	public function getControl() {
		$input = BaseControl::getControl();
		$items = $this->getItems();
		$ids = [];
		if ($this->generateId) {
			foreach ($items as $value => $label) {
				$ids[$value] = $input->id . '-' . $value;
			}
		}

		return $this->container->setHtml(
			Helpers::createInputList(
				$this->translate ? $this->translate($items) : $items,
				array_merge($input->attrs, [
					'id:' => $ids,
					'checked?' => $this->value,
					'disabled:' => $this->disabled,
					'data-nette-rules:' => [key($items) => $input->attrs['data-nette-rules']],
				]),
				['for:' => $ids] + $this->itemLabel->attrs,
				$this->separator
			)
		);
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
