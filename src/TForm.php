<?php declare(strict_types=1);

namespace WebChemistry\Controls;

trait TForm {

	public function addDate(string $name, ?string $label = null, ?int $maxLength = null): DateInput {
		return $this[$name] = new DateInput($label, $maxLength);
	}

	public function addSuggestion(string $name, callable $callback, ?string $label = null): SuggestionInput {
		return $this[$name] = new SuggestionInput($callback, $label, null);
	}

	public function addDateSelect(string $name, ?string $label): DateSelectInput {
		return $this[$name] = new DateSelectInput($label);
	}

}
