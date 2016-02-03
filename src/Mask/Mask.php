<?php

namespace WebChemistry\Forms\Controls;

use Nette;
use WebChemistry\Forms\ControlException;

class Mask extends Nette\Forms\Controls\TextInput {

	const VALID = ':wchMask';

	/** @var array */
	protected $settings = [];

	/** @var bool */
	protected $isSetRule = FALSE;

	/** @var string */
	protected $regex;

	/** @var string */
	protected $errorMessage;

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

	/**
	 * @return string
	 */
	protected function getErrorMessage() {
		if ($this->errorMessage) {
			return $this->errorMessage;
		} else if (isset(Nette\Forms\Validator::$messages[self::VALID])) {
			return Nette\Forms\Validator::$messages[self::VALID];
		} else {
			return 'Please enter a value in the required format.';
		}
	}

	private function createRule() {
		if (!$this->isSetRule) {
			$this->addCondition(Nette\Forms\Form::FILLED)
				 ->addRule(Nette\Forms\Form::PATTERN, $this->getErrorMessage(), $this->regex);

			$this->isSetRule = TRUE;
		}
	}

	/**
	 * @param string $regex
	 * @param string $message
	 * @return Mask
	 * @throws ControlException
	 */
	public function setRegex($regex, $message = NULL) {
		if ($this->isSetRule) {
			throw new ControlException('Rule is already set.');
		}
		$this->settings['regex'] = $this->regex = $regex;
		$this->errorMessage = $message;

		return $this;
	}

	/**
	 * 9 - numeric, * - alphanumeric, a - alphabetical
	 *
	 * @param string $mask
	 * @param string $message
	 * @return Mask
	 * @throws ControlException
	 */
	public function setMask($mask, $message = NULL) {
		if ($this->isSetRule) {
			throw new ControlException('Rule is already set.');
		}

		$this->settings['mask'] = $mask;
		$this->regex = str_replace(array('9', 'a', '\*'), array('[0-9]', '[a-zA-Z]', '[0-9a-zA-Z]'), preg_quote($mask));
		$this->errorMessage = $message;

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
