<?php

namespace WebChemistry\Forms\Controls;

use Nette;
use WebChemistry\Forms\Traits\TLiveValidation;

class RadioList extends Nette\Forms\Controls\RadioList{

	use TLiveValidation;

	/**
	 * Generates control's HTML element.
	 *
	 * @return Nette\Utils\Html
	 */
	public function getControl() {
		return $this->addLiveValidationAttribute(parent::getControl());
	}
}