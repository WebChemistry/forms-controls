<?php

namespace WebChemistry\Forms\Traits;

use WebChemistry\Forms\Controls\Suggestion;

trait TSuggestion {

	/**
	 * @param string $path
	 * @param string $term
	 */
	public function actionSuggestion($path, $term) {
		$lookup = base64_decode($path);
		$data = [];
		$component = $this;

		foreach (explode('-', $lookup) as $name) {
			$current = $component->getComponent($name, FALSE);

			if (!$current) {
				$component = NULL;
				break;
			}

			$component = $current;
		}
		if ($component) {
			if ($component instanceof Suggestion) {
				$data = $component->call($term);
			}
		}

		if ($data instanceof \Traversable) {
			$data = iterator_to_array($data);
		}

		$this->sendJson((array) $data);
	}

}
