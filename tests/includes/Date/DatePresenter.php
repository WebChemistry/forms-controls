<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use WebChemistry\Forms\Controls\Date;

class DatePresenter extends Presenter {

	public function renderDefault() {
		$this->terminate();
	}

	protected function createComponentForm() {
		$form = new \Form;

		$form->addDate('date', '', 'j.m.Y H:i')
			 ->setType(Date::TIMESTAMP);

		return $form;
	}

	protected function createComponentDefaultDate() {
		$form = new \Form;

		$form->addDate('date');

		return $form;
	}

}
