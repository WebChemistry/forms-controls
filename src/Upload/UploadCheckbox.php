<?php

namespace WebChemistry\Images\Controls;

use Nette\Utils\Html;

class UploadCheckbox extends \Nette\Forms\Controls\Checkbox {

	const CHECKBOX_NAME = '_checkbox';

	/** @var string */
	private $prepend;

	/** @var string */
	private $file;

	/** @var string */
	public static $labelContent = 'Delete this file?';

	/** @var string */
	private $wwwDir;

	public function __construct($label = NULL) {
		parent::__construct($label ? : self::$labelContent);
	}

	/**
	 * @return bool
	 */
	public function isOk() {
		return $this->file && file_exists($this->file);
	}

	/************************* Getters **************************/

	/**
	 * @return string
	 */
	public function getControl() {
		if (!$this->isOk()) {
			return NULL;
		}
		$html = Html::el('div')->setClass('file-upload-container');
		$file = Html::el('span')->setText(basename($this->file));
		$html->setHtml($file);

		return $html . parent::getControl();
	}

	/**
	 * @return string
	 */
	public function getHtmlName() {
		return $this->prepend . self::CHECKBOX_NAME;
	}

	/**
	 * @return null
	 */
	public function getHtmlId() {
		return NULL;
	}

	/************************* Setters **************************/

	/**
	 * @param string $prepend
	 * @return UploadCheckbox
	 */
	public function setPrepend($prepend) {
		$this->prepend = $prepend;

		return $this;
	}
	/**
	 * @param string $file
	 * @return UploadCheckbox
	 */
	public function setFile($file) {
		$this->file = $file;

		return $this;
	}

	/**
	 * @param string $wwwDir
	 * @return UploadCheckbox
	 */
	public function setWwwDir($wwwDir) {
		$this->wwwDir = $wwwDir;

		return $this;
	}

}
