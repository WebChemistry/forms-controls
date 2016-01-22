<?php

namespace WebChemistry\Forms\Controls;

use WebChemistry\Forms\Controls;

trait TForm {

	/**
	 * @param bool  $deep
	 * @param array $controls
	 */
	public function cleanErrors($deep = FALSE, $controls = array()) {
		parent::cleanErrors();

		if ($deep) {
			foreach ($controls ? : $this->getControls() as $control) {
				$control->cleanErrors();
			}
		}
	}

	/**
	 * @param string $name
	 * @param null   $label
	 * @return Recaptcha
	 * @throws \Exception
	 */
	public function addRecaptcha($name, $label = NULL) {
		if ($this->isRecaptcha) {
			throw new \Exception('Recaptcha: You can add only one.');
		}

		if (!$this->recaptcha['secret']) {
			throw new \Exception('Recaptcha: You must set secret key in config.');
		}

		if (!$this->recaptcha['recaptcha']['api']) {
			throw new \Exception('Recaptcha: You must set api key in config.');
		}

		$this->isRecaptcha = TRUE;

		return $this[$name] = new Recaptcha($this->recaptcha['api'], $this->recaptcha['secret'], $label);
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @return Editor
	 */
	public function addEditor($name, $label = NULL) {
		return $this[$name] = new Editor($label);
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @return Tags
	 */
	public function addTags($name, $label = NULL) {
		return $this[$name] = new Tags($label);
	}

	/**
	 * @param string   $name
	 * @param string   $label
	 * @param callback $callback
	 * @return Suggestion
	 */
	public function addSuggestion($name, $label = NULL, $callback = NULL) {
		$control = new Suggestion($callback, $label);

		return $this[$name] = $control;
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @param string $mask
	 * @param int    $maxLength
	 * @return Mask
	 */
	public function addMask($name, $label = NULL, $mask = NULL, $maxLength = NULL) {
		$control = new Mask($label, $maxLength);

		if ($mask) {
			$control->setMask($mask);
		}

		return $this[$name] = $control;
	}

	/**
	 * @param string $name
	 * @param string $caption
	 * @return Date
	 */
	public function addDate($name, $caption = NULL, $format = 'j.m.Y H:i') {
		$control = new Date($caption, $format);

		return $this[$name] = $control;
	}

	/**
	 * Adds single-line text input control to the form.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  int     width of the control (deprecated)
	 * @param  int     maximum number of characters the user may enter
	 * @return Controls\TextInput
	 */
	public function addText($name, $label = NULL, $cols = NULL, $maxLength = NULL) {
		$control = new Controls\TextInput($label, $maxLength);
		$control->setAttribute('size', $cols);

		return $this[$name] = $control;
	}

	/**
	 * Adds single-line text input control used for sensitive input such as passwords.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  int     width of the control (deprecated)
	 * @param  int     maximum number of characters the user may enter
	 * @return Controls\TextInput
	 */
	public function addPassword($name, $label = NULL, $cols = NULL, $maxLength = NULL) {
		$control = new Controls\TextInput($label, $maxLength);
		$control->setAttribute('size', $cols);

		return $this[$name] = $control->setType('password');
	}

	/**
	 * Adds multi-line text input control to the form.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  int     width of the control
	 * @param  int     height of the control in text lines
	 * @return Controls\TextArea
	 */
	public function addTextArea($name, $label = NULL, $cols = NULL, $rows = NULL) {
		$control = new Controls\TextArea($label);
		$control->setAttribute('cols', $cols)
				->setAttribute('rows', $rows);

		return $this[$name] = $control;
	}

	/**
	 * Adds control that allows the user to upload files.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  bool    allows to upload multiple files
	 * @return Controls\UploadControl
	 */
	public function addUpload($name, $label = NULL, $multiple = FALSE) {
		return $this[$name] = new Controls\UploadControl($label, $multiple);
	}

	/**
	 * Adds control that allows the user to upload multiple files.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @returnControls\UploadControl
	 */
	public function addMultiUpload($name, $label = NULL) {
		return $this[$name] = new Controls\UploadControl($label, TRUE);
	}

	/**
	 * Adds hidden form control used to store a non-displayed value.
	 *
	 * @param  string  control name
	 * @param  mixed   default value
	 * @return Controls\HiddenField
	 */
	public function addHidden($name, $default = NULL) {
		$control = new Controls\HiddenField;
		$control->setDefaultValue($default);

		return $this[$name] = $control;
	}

	/**
	 * Adds check box control to the form.
	 *
	 * @param  string  control name
	 * @param  string  caption
	 * @return Controls\Checkbox
	 */
	public function addCheckbox($name, $caption = NULL) {
		return $this[$name] = new Controls\Checkbox($caption);
	}

	/**
	 * Adds set of radio button controls to the form.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  array   options from which to choose
	 * @return Controls\RadioList
	 */
	public function addRadioList($name, $label = NULL, array $items = NULL) {
		return $this[$name] = new Controls\RadioList($label, $items);
	}

	/**
	 * Adds select box control that allows single item selection.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  array   items from which to choose
	 * @param  int     number of rows that should be visible
	 * @return Controls\SelectBox
	 */
	public function addSelect($name, $label = NULL, array $items = NULL, $size = NULL) {
		$control = new Controls\SelectBox($label, $items);
		if ($size > 1) {
			$control->setAttribute('size', (int) $size);
		}

		return $this[$name] = $control;
	}

	/**
	 * Adds select box control that allows multiple item selection.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  array   options from which to choose
	 * @param  int     number of rows that should be visible
	 * @return Controls\MultiSelectBox
	 */
	public function addMultiSelect($name, $label = NULL, array $items = NULL, $size = NULL) {
		$control = new Controls\MultiSelectBox($label, $items);
		if ($size > 1) {
			$control->setAttribute('size', (int) $size);
		}

		return $this[$name] = $control;
	}

	/**
	 * Adds control that allows the user to upload files.
	 *
	 * @param  string  control name
	 * @param  string  label
	 * @param  string
	 * @param  bool    allows to upload multiple files
	 * @return Controls\Upload
	 */
	public function addPreviewUpload($name, $label = NULL, $uploadPath = NULL) {
		return $this[$name] = new Controls\Upload($label, $uploadPath);
	}

}
