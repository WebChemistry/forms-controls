<?php

namespace WebChemistry\Forms\Traits;

use Nette\Forms\Rule;
use Nette\Forms\Rules;

trait TLiveValidation {

	/** @var bool */
	protected $liveValidation = TRUE;

	/**
	 * @param bool $liveValidation
	 * @return $this
	 */
	public function setLiveValidation($liveValidation = TRUE) {
		$this->liveValidation = $liveValidation;

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isLiveValidation() {
		return $this->liveValidation && $this->hasOwnErrors($this->getRules());
	}

	/**
	 * @param $control
	 * @return mixed
	 */
	public function addLiveValidationAttribute($control) {
		if ($this->isLiveValidation()) {
			$control->data('live-validation', $this->lookupPath('Nette\Application\IPresenter', FALSE));
		}

		return $control;
	}

	/**
	 * @param Rules $rules
	 * @return bool
	 */
	private function hasOwnErrors(Rules $rules) {
		/** @var Rule $rule */
		foreach ($rules as $rule) {
			if (is_callable($rule->validator)) {
				return TRUE;
			}

			if ($rule->branch) {
				if ($this->hasOwnErrors($rule->branch)) {
					return TRUE;
				}
			}
		}
	}
}