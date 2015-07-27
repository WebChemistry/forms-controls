<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;
use WebChemistry\Forms\Controls\Recaptcha;

class RecaptchaPresenter extends Presenter {

	public function renderDefault() {
		$this->terminate();
	}

	protected function createComponentForm() {
		$form = new \Form;

		$form['recaptcha'] = new Recaptcha('a4f45afs45afs', '5ag48ga4gea8aeg');

		return $form;
	}
}