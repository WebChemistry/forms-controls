<?php

namespace WebChemistry\Forms\Controls;

use Nette\Application\IPresenter;
use Nette\Forms\Controls\TextInput;

class Tags extends TextInput {

	/** @var bool */
	private $usePlaceholder = TRUE;

	/**
	 * @param string $label
	 * @param string $maxLength
	 */
	public function __construct($label = NULL, $maxLength = NULL) {
		parent::__construct($label, $maxLength);

		$this->monitor('Nette\Application\IPresenter');

	}

	/**
	 * @param bool|FALSE $value
	 * @return Tags
	 */
	public function usePlaceholder($value = FALSE) {
		$this->usePlaceholder = $value;

		return $this;
	}

	/**
	 * Changes control's HTML attribute.
	 *
	 * @param  string name
	 * @param  mixed  value
	 * @return self
	 */
	public function setAttribute($name, $value = TRUE) {
		if ($name === 'placeholder') {
			$this->usePlaceholder = FALSE;
		}

		return parent::setAttribute($name, $value);
	}

	private function createPlaceholder() {
		if ($this->usePlaceholder === FALSE) {
			return;
		}

		$message = 'For the distribution of words please use comma.';

		if ($this->getTranslator()) {
			$message = $this->getTranslator()->translate($message);
		}

		$this->setAttribute('placeholder', $message);
	}

	/**
	 * This method will be called when the component becomes attached to Form.
	 *
	 * @param  Nette\ComponentModel\IComponent
	 * @return void
	 */
	protected function attached($form) {
		parent::attached($form);

		if ($form instanceof IPresenter) {
			$this->createPlaceholder();
		}
	}

	/**
	 * @return array
	 */
	public function getValue() {
		$value = parent::getValue();

		if (!$value) {
			return NULL;
		} else if (is_array($value)) {
			return $value;
		}

		return array_map(function ($value) {
			return trim($value);
		}, explode(',', $value));
	}

	/**
	 * @return \Nette\Utils\Html
	 */
	public function getControl() {
		$control = parent::getControl();

		$control->value = implode(',', (array) $this->getValue());
		$control->class[] = 'tag-input';

		return $control;
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value) {
		$this->rawValue = $this->value = $value;
	}
}