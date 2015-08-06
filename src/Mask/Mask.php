<?php

namespace WebChemistry\Forms\Controls;

use Nette;

class Mask extends Nette\Forms\Controls\TextInput{

	/** @var array */
	protected $settings = array();

	/** @var bool */
	protected $isSetRule = FALSE;

	/** @var string */
	protected $regex;

	/** @var string */
	protected $message;

	/**
	 * @param  string $label  label
	 * @param  int    $maxLength maximum number of characters the user may enter
	 */
	public function __construct($label = NULL, $maxLength = NULL) {
		parent::__construct($label, $maxLength);

		$this->monitor('Nette\Application\IPresenter');
	}

	/**
	 * Loads HTTP data.
	 *
	 * @return void
	 */
	public function loadHttpData() {
		$this->createRule();

		parent::loadHttpData();
	}

	private function createRule() {
		if (!$this->isSetRule) {
			$message = $this->message;

			if ($this->getTranslator()) {
				$message = $this->getTranslator()->translate($message);
			}

			$this->addCondition(Nette\Forms\Form::FILLED)
				 ->addRule(Nette\Forms\Form::PATTERN, $message, $this->regex);

			$this->isSetRule = TRUE;
		}
	}

	/**
	 * @param string $regex
	 * @param string $message
	 * @return Mask
	 */
	public function setRegex($regex, $message = NULL) {
		if ($this->isSetRule) {
			throw new \Exception('Rule is already set.');
		}

		$this->settings['regex'] = $this->regex = $regex;

		$this->message = $message ? : 'Please enter a value in the required format.';

		return $this;
	}

	/**
	 * 9 - numeric, * - alphanumeric, a - alphabetical
	 *
	 * @param string $mask
	 * @param string $message
	 * @return Mask
	 */
	public function setMask($mask, $message = NULL) {
		if ($this->isSetRule) {
			throw new \Exception('Rule is already set.');
		}

		$this->settings['mask'] = $mask;

		$this->regex = str_replace(array('9', 'a', '\*'), array('[0-9]', '[a-zA-Z]', '[0-9a-zA-Z]'), preg_quote($mask));
		$this->message = $message ? : 'Please enter a value in the required format.';

		return $this;
	}

	/**
	 * @param array $settings
	 * @return Mask
	 */
	public function setSettings(array $settings) {
		$this->settings = array_merge($this->settings, $settings);

		return $this;
	}

	/**
	 * @return Nette\Utils\Html
	 */
	public function getControl() {
		$control = parent::getControl();

		$this->createRule();

		if ($this->settings) {
			$control->data('mask-input', $this->settings);
		}

		return $control;
	}
}
