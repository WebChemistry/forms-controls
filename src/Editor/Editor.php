<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\TextArea;

class Editor extends TextArea {

	/**
	 * @param string $value
	 * @return \Nette\Forms\Controls\TextBase
	 */
	public function setValue($value) {
		return parent::setValue(html_entity_decode($value));
	}

	/**
	 * @return \Nette\Utils\Html
	 */
	public function getControl() {
		$control = parent::getControl();

		$control->class[] = 'editor-input ckeditor';
		$control->data('novalidate', '');

		return $control;
	}

}
