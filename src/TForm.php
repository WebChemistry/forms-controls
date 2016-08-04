<?php

namespace WebChemistry\Forms\Controls;

use WebChemistry\Forms\ControlException;
use WebChemistry\Forms\Controls;

trait TForm {

	/** @deprecated */
	private $recaptcha = [
		'api' => NULL,
		'secret' => NULL
	];

	/** @var bool */
	private $hasRecaptcha = FALSE;

	/**
	 * @deprecated
	 */
	public function setRecaptchaConfig(array $recaptchaConfig) {
		$this->recaptcha = array_merge($this->recaptcha, $recaptchaConfig);

		return $this;
	}

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
	 * @param string $label
	 * @return Recaptcha
	 * @throws ControlException
	 */
	public function addRecaptcha($name, $label = NULL) {
		if ($this->hasRecaptcha) {
			throw new ControlException('Recaptcha: You can add only one.');
		}
		$this->hasRecaptcha = TRUE;

		$this[$name] = $recaptcha = new Recaptcha($label);

		// deprecated
		if ($this->recaptcha['api']) {
			$recaptcha->setApiKey($this->recaptcha['api']);
		}
		if ($this->recaptcha['secret']) {
			$recaptcha->setSecretKey($this->recaptcha['secret']);
		}

		return $recaptcha;
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
	 * @param string $format
	 * @return Date
	 */
	public function addDate($name, $caption = NULL, $format = NULL) {
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

	/**
	 * Adds select box control that allows single item selection.
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
	 * Adds set of radio button controls to the form.
	 * @param  string  control name
	 * @param  string  label
	 * @param  array   options from which to choose
	 * @return Controls\RadioList
	 */
	public function addRadioList($name, $label = NULL, array $items = NULL) {
		return $this[$name] = new Controls\RadioList($label, $items);
	}

	/**
	 * Adds set of checkbox controls to the form.
	 * @return Controls\CheckboxList
	 */
	public function addCheckboxList($name, $label = NULL, array $items = NULL) {
		return $this[$name] = new Controls\CheckboxList($label, $items);
	}

	/**
	 * Adds select box control that allows multiple item selection.
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

}
