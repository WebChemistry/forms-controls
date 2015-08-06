<?php

namespace WebChemistry\Forms\Controls;

use Nette;
use WebChemistry\Forms\ClientSide;
use WebChemistry\Forms\Exception;

class Suggestion extends Nette\Forms\Controls\TextInput {

	/** @var array */
	protected $settings = array();

	/** @var callback */
	protected $callback;

	/** @var string */
	protected $link;

	/**
	 * @param callback $callback
	 * @param string   $label
	 * @param int      $maxLength
	 */
	public function __construct($callback = NULL, $label = NULL, $maxLength = NULL) {
		parent::__construct($label, $maxLength);

		$this->callback = $callback;

		$this->monitor('Nette\Application\IPresenter');
	}

	/**
	 * @param callback $callback
	 * @return Suggestion
	 */
	public function setCallback($callback) {
		$this->callback = $callback;

		return $this;
	}

	/**
	 * @param string $link
	 * @return Suggestion
	 */
	public function setLink($link) {
		$this->link = $link;

		return $this;
	}

	/**
	 * @param array $settings
	 * @return Suggestion
	 */
	public function setSettings(array $settings) {
		$this->settings = $settings;

		return $this;
	}

	/**
	 * @param array $settings
	 * @return Suggestion
	 */
	public function addSettings(array $settings) {
		$this->settings = array_merge_recursive($settings, $this->settings);

		return $this;
	}

	/**
	 * @param $q
	 * @return array
	 */
	public function call($q) {
		if (!is_callable($this->callback)) {
			throw new Exception('Suggestion: You must set callable callback.');
		}

		return call_user_func($this->callback, $q);
	}

	/**
	 * @return Nette\Utils\Html
	 */
	public function getControl($needLink = TRUE) {
		$control = parent::getControl();

		if (!$this->link) {
			$presenter = $this->lookup('Nette\Application\IPresenter', FALSE);

			if ($presenter || $needLink) {
				$url = $this->lookup('Nette\Application\IPresenter')
							->link('suggestion', array('path' => base64_encode($this->lookupPath('Nette\Application\IPresenter'))));

				$control->data('url', urldecode($url));
			}
		} else {
			$url = $this->link;

			$control->data('url', urldecode($url));
		}

		$control->class[] = 'suggestion-input';

		$control->data('suggestion', $this->settings);

		return $control;
	}
}
