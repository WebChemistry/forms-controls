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
