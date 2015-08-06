<?php

namespace WebChemistry\Forms\Traits;

use Nette\Forms\IControl;

trait TPresenter {

	/**
	 * @param string $value
	 * @param string $path
	 */
	public function handleLiveValidation($value, $path) {
		if ($path === NULL) {
			$this->error('Path parameter is missing.');
		}

		if ($this->getComponent($path, FALSE)) {
			$component = $this->getComponent($path, FALSE);

			if ($component instanceof IControl && method_exists($component, 'isLiveValidation') && $component->isLiveValidation()) {
				$component->setValue($value);
				$component->validate();

				if ($component->hasErrors()) {
					$errors = $component->getErrors();

					$this->sendJson(array('message' => reset($errors)));
				}
			}
		}

		$this->sendJson(array('message' => NULL));
	}
}