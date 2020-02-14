<?php declare(strict_types=1);

namespace WebChemistry\Controls;

trait TPresenter {

	/**
	 * @param string $path
	 * @param string $term
	 */
	public function actionSuggestionInput(string $path, ?string $term): void {
		$lookup = base64_decode($path);
		$data = [];
		$component = $this;
		foreach (explode('-', $lookup) as $name) {
			$current = $component->getComponent($name, false);
			if (!$current) {
				$component = null;
				break;
			}
			$component = $current;
		}
		if ($component) {
			if ($component instanceof SuggestionInput) {
				$data = $component->call($term, $this->getParameters());
			}
		}
		if ($data instanceof \Traversable) {
			$data = iterator_to_array($data);
		}

		$this->sendJson((array) $data);
	}

}
