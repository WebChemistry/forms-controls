<?php

namespace WebChemistry\Forms\Controls;

use Nette\Application\IPresenter;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\Form;
use Nette\Forms\Validator;
use Nette\Http\FileUpload;
use Nette\Utils\Random;
use WebChemistry\Images\Controls\UploadCheckbox;
use WebChemistry\Forms\ControlException;

class Upload extends UploadControl {

	const UPLOAD_FILLED = 'webChemistryUpload';

	/** @var UploadCheckbox */
	private $checkbox;

	/** @var string */
	private $defaultValue;

	/** @var bool */
	private $delete = FALSE;

	/** @var bool */
	private $isValidated = FALSE;

	/** @var string */
	private $uploadPath;

	public function __construct($label, $uploadPath) {
		parent::__construct($label, FALSE);

		if ($uploadPath) {
			$this->setUploadPath($uploadPath);
		}
		$this->checkbox = new UploadCheckbox();
		$this->monitor('Nette\Application\IPresenter');
	}

	/**
	 * @return string
	 */
	protected function getFilledMessage() {
		if (isset(Validator::$messages[self::UPLOAD_FILLED])) {
			return Validator::$messages[self::UPLOAD_FILLED];
		} else {
			return 'You must agree with deleting of file.';
		}
	}

	protected function attached($form) {
		parent::attached($form);

		if ($form instanceof Form) {
			$this->checkbox->setParent($form, $form->getName());
			if (!$form->onSuccess) {
				$form->onSuccess = [];
			}
			array_unshift($form->onSuccess, [$this, 'successCallback']);
		}

		if ($form instanceof IPresenter) {
			$this->checkbox->setPrepend($this->getHtmlName());
			$this->checkbox->setFile($this->defaultValue);
			$this->checkbox->setWwwDir($form->context->parameters['wwwDir']);
		}
	}

	public function loadHttpData() {
		parent::loadHttpData();

		if (!$this->uploadPath) {
			throw new \Exception('Path for uploading must be set.');
		}

		$this->validate();
		$this->isValidated = TRUE; // Disable validation

		if ($this->checkbox->isOk()) { // Checkbox process
			$this->checkbox->loadHttpData();
			$this->delete = $this->checkbox->getValue();
			if ($this->delete && !$this->isRequired()) {
				$this->value = NULL;
			} else if (!$this->isRequired()) {
				$this->value = $this->defaultValue;
			}
		} else if (!$this->value->isOk()) {
			$this->value = NULL;
		}

		if ($this->isRequired() && $this->checkbox->isOk() && !$this->checkbox->getValue()) {
			$this->addError($this->getFilledMessage());
			return;
		}
	}

	public function successCallback() {
		if ($this->delete) {
			@unlink($this->defaultValue);
		}

		if ($this->value instanceof FileUpload && $this->value->isOk()) { // Upload
			$this->value = $this->upload($this->value);
			$this->checkbox->setFile($this->value);
		}
	}

	/************************* Setters **************************/

	/**
	 * @param string $value
	 * @return Upload
	 */
	public function setValue($value) {
		$this->defaultValue = $value;

		return $this;
	}

	/**
	 * @param string $uploadPath
	 * @return Upload
	 * @throws ControlException
	 */
	public function setUploadPath($uploadPath) {
		if (!file_exists($uploadPath) || !is_dir($uploadPath)) {
			throw new ControlException('Path for uploading not exists.');
		}
		$this->uploadPath = $uploadPath;

		return $this;
	}

	/************************* Getters **************************/

	/**
	 * @return UploadCheckbox
	 */
	public function getCheckbox() {
		return $this->checkbox;
	}

	/**
	 * @return string
	 */
	public function getControl() {
		if ($this->checkbox->isOk()) {
			return ($this->isRequired() ? parent::getControl() : NULL) . $this->checkbox->getControl();
		}
		return parent::getControl();
	}

	/**
	 * @param string $caption
	 * @return \Nette\Utils\Html|string
	 */
	public function getLabel($caption = NULL) {
		if ($this->checkbox->isOk()) {
			return $this->isRequired() ? parent::getLabel($caption) : NULL;
		}
		return parent::getLabel($caption);
	}

	/************************* Uploder **************************/

	/**
	 * @param \Nette\Http\FileUpload $fileUpload
	 * @return string
	 */
	protected function upload(FileUpload $fileUpload) {
		$fileName = $fileUpload->getSanitizedName();
		while (file_exists($this->uploadPath . '/' . $fileName)) {
			$fileName = Random::generate() . $fileUpload->getSanitizedName();
		}
		$uploadPath = $this->uploadPath . '/' . $fileName;

		$fileUpload->move($uploadPath);

		return $uploadPath;
	}

}
