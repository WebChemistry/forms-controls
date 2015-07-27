<?php

use Tester\Assert as A;

class SuggestionTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		A::$onFailure = array($this, 'failure');
	}

	public function failure(\Exception $e) {
		$this->fail($e->getMessage());
	}

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

		A::throws(function () use ($sug) {
			$sug->getControl();
		}, 'Nette\InvalidStateException');

		$this->assertInstanceOf('Nette\Utils\Html', $sug->getControl(FALSE));

		$sug->setLink('link');

		$this->assertInstanceOf('Nette\Utils\Html', $sug->getControl());
	}
}
