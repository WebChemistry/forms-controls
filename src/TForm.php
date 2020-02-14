<?php declare(strict_types=1);

namespace WebChemistry\Controls;

trait TForm {

	public function addDate(string $name, ?string $label = null, ?int $maxLength = null): DateInput {
		return $this[$name] = new DateInput($label, $maxLength);
	}

	public function addSuggestion(string $name, ?string $label = null, callable $callback): SuggestionInput {
		return $this[$name] = new SuggestionInput($label, null, $callback);
	}

	public function addDateSelect(string $name, ?string $label): DateSelectInput {
		return $this[$name] = new DateSelectInput($label);
	}

}
