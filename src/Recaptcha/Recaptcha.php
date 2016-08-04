<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\TextArea;
use Nette\Forms\Validator;
use Nette\Http\Url;
use WebChemistry\Forms\ControlException;

class Recaptcha extends TextArea {

	const FILLED = ':wchRecaptchaFilled';

	const VALID = ':wchRecaptchaError';

	/** @var string */
	private $apiKey;

	/** @var string */
	private $secretKey;

	/**
	 * @param string $apiKey
	 * @param string $secretKey
	 * @param string $label
	 */
	public function __construct($apiKey, $secretKey, $label = NULL) {
		parent::__construct($label);

		$this->setApiKey($apiKey);
		$this->setSecretKey($secretKey);
		$this->setOmitted();
		$this->addRule([$this, 'validateRecaptcha']);
	}

	/**
	 * @return string
	 */
	protected function getValidMessage() {
		if (isset(Validator::$messages[self::VALID])) {
			return Validator::$messages[self::VALID];
		} else {
			return 'Antispam detection was not successful.';
		}
	}

	/**
	 * @return string
	 */
	protected function getFilledMessage() {
		if (isset(Validator::$messages[self::FILLED])) {
			return Validator::$messages[self::FILLED];
		} else {
			return 'Please fill antispam.';
		}
	}

	/**
	 * @return bool
	 */
	public function validateRecaptcha() {
		$httpData = $this->getForm()->getHttpData();
		if (!isset($httpData['g-recaptcha-response'])) {
			$this->addError($this->getFilledMessage());

			return TRUE;
		}

		$url = new Url();
		$url->setHost('www.google.com')
			->setScheme('https')
			->setPath('recaptcha/api/siteverify')
			->setQueryParameter('secret', $this->secretKey)
			->setQueryParameter('response', $httpData['g-recaptcha-response']);
		$data = json_decode(file_get_contents((string) $url));

		if (isset($data->success) && $data->success === TRUE) {
			return TRUE;
		} else {
			$this->addError($this->getValidMessage());

			return TRUE;
		}
	}

	/**
	 * @param string $apiKey
	 * @return Recaptcha
	 * @throws ControlException
	 */
	public function setApiKey($apiKey) {
		if (!is_string($apiKey)) {
			throw new ControlException('Recaptcha: Api key must be string.');
		}
		$this->apiKey = $apiKey;

		return $this;
	}

	/**
	 * @param string $secretKey
	 * @return Recaptcha
	 * @throws ControlException
	 */
	public function setSecretKey($secretKey) {
		if (!is_string($secretKey)) {
			throw new ControlException('Recaptcha: Secret key must be string.');
		}
		$this->secretKey = $secretKey;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getApiKey() {
		return $this->apiKey;
	}

	/**
	 * @return string
	 */
	public function getControl() {
		ob_start();
		$apiKey = $this->getApiKey();
		require __DIR__ . '/templates/recaptcha.phtml';

		return ob_get_clean();
	}

}
