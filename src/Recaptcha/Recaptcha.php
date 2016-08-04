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

	/** @var string */
	private static $defaultApiKey;

	/** @var string */
	private static $defaultSecretKey;

	/**
	 * @param string $label
	 */
	public function __construct($label = NULL) {
		parent::__construct($label);

		$this->setOmitted();
		$this->addRule([$this, 'validateRecaptcha']);
	}

	/**
	 * @return string
	 */
	protected function getValidMessage() {
		return isset(Validator::$messages[self::VALID]) ? Validator::$messages[self::VALID] : 'Antispam detection was not successful.';
	}

	/**
	 * @return string
	 */
	protected function getFilledMessage() {
		return isset(Validator::$messages[self::FILLED]) ? Validator::$messages[self::FILLED] : 'Please fill antispam.';
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

		$this->validateKeys();
		$url = new Url();
		$url->setHost('www.google.com')
			->setScheme('https')
			->setPath('recaptcha/api/siteverify')
			->setQueryParameter('secret', $this->secretKey)
			->setQueryParameter('response', $httpData['g-recaptcha-response']);
		$data = json_decode(file_get_contents((string) $url));

		if (!isset($data->success) || $data->success !== TRUE) {
			$this->addError($this->getValidMessage());
		}

		return TRUE;
	}

	/**
	 * @param string $apiKey
	 * @return Recaptcha
	 */
	public function setApiKey($apiKey) {
		$this->apiKey = $apiKey;

		return $this;
	}

	/**
	 * @param string $secretKey
	 * @return Recaptcha
	 */
	public function setSecretKey($secretKey) {
		$this->secretKey = $secretKey;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getApiKey() {
		return $this->apiKey ?: self::$defaultApiKey;
	}

	protected function validateKeys() {
		$apiKey = $this->getApiKey();
		$sercretKey = $this->secretKey ?: self::$defaultSecretKey;
		if (!$apiKey || !$sercretKey) {
			throw new ControlException('Api and secret key must be set.');
		}
	}

	/**
	 * @return string
	 */
	public function getControl() {
		$this->validateKeys();
		ob_start();
		$apiKey = $this->getApiKey();
		require __DIR__ . '/templates/recaptcha.phtml';

		return ob_get_clean();
	}

	/**
	 * @param string $apiKey
	 * @param string $secretKey
	 */
	public static function configure($apiKey, $secretKey) {
		self::$defaultApiKey = $apiKey;
		self::$defaultSecretKey = $secretKey;
	}

}
