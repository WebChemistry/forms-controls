<?php

namespace WebChemistry\Forms\Controls;

use Nette;
use WebChemistry\Forms\Traits\TLiveValidation;

class UploadControl extends Nette\Forms\Controls\UploadControl{

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