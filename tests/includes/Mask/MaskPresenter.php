<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use WebChemistry\Forms\Controls\Recaptcha;

class MaskPresenter extends Presenter {

	public function renderDefault() {
		$this->terminate();
	}

	protected function createComponentForm() {
		$form = new \Form;

		$form->addMask('mask')
			 ->setMask('999 aaa');

		$form->addMask('regex')
			 ->setRegex('[0-9]{3} [a-z]{3}');

		return $form;
	}

	protected function createComponentRequired() {
		$form = new \Form;

		$form->addMask('mask')
			 ->setRequired()
			 ->setMask('999 aaa');

		$form->addMask('regex')
			 ->setRequired()
			 ->setRegex('[0-9]{3} [a-z]{3}');

		return $form;
	}
}