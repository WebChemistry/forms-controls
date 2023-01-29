<?php declare(strict_types=1);

namespace WebChemistry\Controls;

use Nette;
use Nette\Forms\Controls\TextInput;

final class SuggestionInput extends TextInput {

	/** @var callable */
	private $callback;

	public function __construct(callable $callback, $label = null, $maxLength = null) {
		parent::__construct($label, $maxLength);
		$this->callback = $callback;

		$this->monitor(Nette\Application\IPresenter::class);
	}

	/**
	 * @param string $q
	 * @param array $parameters
	 * @return array
	 */
	public function call(string $q, array $parameters = []): array {
		return call_user_func($this->callback, $q, $parameters);
	}

	public function getControl(): Nette\Utils\Html {
		$control = parent::getControl();
		$control->appendAttribute('class', 'suggestion-input');

		$presenter = $this->lookup(Nette\Application\IPresenter::class);
		$url = $presenter->link('suggestionInput', [
			'path' => base64_encode($this->lookupPath(Nette\Application\IPresenter::class))
		]);
		$control->data('url', urldecode($url));

		return $control;
	}

}
