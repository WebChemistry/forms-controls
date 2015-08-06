<?php

namespace WebChemistry\Forms\Controls;


class Editor extends TextArea {

	/**
	 * @param $value
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
