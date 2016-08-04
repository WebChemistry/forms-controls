<?php

namespace WebChemistry\Forms\Controls;

use Nette\Application\IPresenter;
use Nette\ComponentModel\IComponent;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Validator;
use Nette\Utils\Html;

class Tags extends TextInput {

	/** @var bool */
	private $usePlaceholder = TRUE;

	/** @deprecated */
	const VALID = ':wchTags';

	const PLACEHOLDER = ':wchTags';

	/**
	 * @param string $label
	 * @param string $maxLength
	 */
	public function __construct($label = NULL, $maxLength = NULL) {
		parent::__construct($label, $maxLength);

		$this->monitor('Nette\Application\IPresenter');

	}

	/**
	 * @param bool $value
	 * @return self
	 */
	public function usePlaceholder($value = FALSE) {
		$this->usePlaceholder = $value;

		return $this;
	}

	/**
	 * Changes control's HTML attribute.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return self
	 */
	public function setAttribute($name, $value = TRUE) {
		if ($name === 'placeholder') {
			$this->usePlaceholder = FALSE;
		}

		return parent::setAttribute($name, $value);
	}

	/**
	 * @return string
	 */
	protected function getMessage() {
		if (isset(Validator::$messages[self::PLACEHOLDER])) {
			return Validator::$messages[self::PLACEHOLDER];
		} else {
			return 'For the distribution of words please use comma.';
		}
	}

	private function createPlaceholder() {
		if ($this->usePlaceholder === FALSE) {
			return;
		}
		$this->setAttribute('placeholder', $this->getMessage());
	}

	/**
	 * This method will be called when the component becomes attached to Form.
	 *
	 * @param IComponent $form
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
	 * @return Html
	 */
	public function getControl() {
		$control = parent::getControl();

		$control->value = implode(',', (array) $this->getValue());
		$control->class[] = 'tag-input';

		return $control;
	}

	/**
	 * @param mixed $value
	 * @return self
	 */
	public function setValue($value) {
		$this->rawValue = $this->value = $value;

		return $this;
	}

}
