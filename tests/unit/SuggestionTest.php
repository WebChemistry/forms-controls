<?php

class SuggestionTest extends \PHPUnit_Framework_TestCase {

	use \WebChemistry\Test\TMethods;

	protected function tearDown() {
	}

	public function suggestionCallback($query) {
		$this->assertSame('query', $query);

		return array(
			'query1',
			'query2'
		);
	}

	public function testCallback() {
		$form = new Form;
		$sug = $form->addSuggestion('suggestion')
					->setCallback(array($this, 'suggestionCallback'));

		$this->assertSame(array(
			'query1',
			'query2'
		), $sug->call('query'));

		$this->assertThrowException(function () use ($sug) {
			$sug->getControl();
		}, 'Nette\InvalidStateException');

		$this->assertInstanceOf('Nette\Utils\Html', $sug->getControl(FALSE));

		$sug->setLink('link');

		$this->assertInstanceOf('Nette\Utils\Html', $sug->getControl());
	}
}
