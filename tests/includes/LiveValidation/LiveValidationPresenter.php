<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

class LiveValidationPresenter extends Presenter {

	public function renderDefault() {
		$this->template->setFile(__DIR__ . '/templates/default.latte');
	}

	public function link($destination, $args = array()) {
		return NULL;
	}

	protected function createComponentForm() {
		$form = new \Form;

		$form->addText('text')
			 ->addRule(array($this, 'myValidation'));

		$form->addSelect('select', NULL, ['items' => 'items'])
			 ->addRule(array($this, 'myValidation'));

		$form->addCheckbox('checkbox')
			 ->addRule(array($this, 'myValidation'));

		$form->addCheckboxList('checkboxList', NULL, ['items' => 'items'])
			 ->addRule(array($this, 'myValidation'));

		$form->addEditor('editor')
			 ->addRule(array($this, 'myValidation'));

		$form->addMask('mask')
			 ->addRule(array($this, 'myValidation'));

		$form->addMultiSelect('multiselect', NULL, ['items' => 'items'])
			 ->addRule(array($this, 'myValidation'));

		$form->addPassword('password')
			 ->addRule(array($this, 'myValidation'));

		$form->addUpload('upload')
			 ->addRule(array($this, 'myValidation'));

		$form->addRadioList('radioList', NULL, ['items' => 'items'])
			 ->addRule(array($this, 'myValidation'));

		$form->addTextArea('textArea')
			 ->addRule(array($this, 'myValidation'));

		$form->addTags('tags')
			 ->addRule(array($this, 'myValidation'));

		return $form;
	}
}