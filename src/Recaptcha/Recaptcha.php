<?php

namespace WebChemistry\Forms\Controls;

use Nette\Forms\Controls\TextArea;
use Nette\Http\Url;
use WebChemistry\Forms\Exception;

class Recaptcha extends TextArea {

	/** @var string */
	private $apiKey;

	/** @var string */
	private $secretKey;

	/**
	 * @param string $apiKey
	 * @param string $secretKey
	 * @param string $label
	 * @throws Exception
	 */
	public function __construct($apiKey, $secretKey, $label = NULL) {
		parent::__construct($label);

		$this->setApiKey($apiKey);
		$this->setSecretKey($secretKey);
		$this->setOmitted();

		$this->addRule(array($this, 'validateRecaptcha'), '');
	}

	public function validateRecaptcha() {
		$httpData = $this->getForm()->getHttpData();

		if (!isset($httpData['g-recaptcha-response'])) {
			$this->addError('Please fill antispam.');

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
			$this->addError('Antispam detection wasn\'t success.');

			return TRUE;
		}
	}

	/**
	 * @param string $apiKey
	 * @return Recaptcha
	 */
	public function setApiKey($apiKey) {
		if (!is_string($apiKey)) {
			throw new Exception('Recaptcha: Api key must be string.');
		}

		$this->apiKey = $apiKey;

		return $this;
	}

	/**
	 * @param string $secretKey
	 * @return Recaptcha
	 */
	public function setSecretKey($secretKey) {
		if (!is_string($secretKey)) {
			throw new Exception('Recaptcha: Secret key must be string.');
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
	 * @return null
	 */
	public function getControl() {
		ob_start();

		$apiKey = $this->getApiKey();

		require __DIR__ . '/templates/recaptcha.phtml';

		return ob_get_clean();
	}
}
