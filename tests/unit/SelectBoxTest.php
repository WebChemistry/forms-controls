<?php

class SelectBoxTest extends \Codeception\TestCase\Test {

	public function testNoTranslate() {
		$form = new Form();

		$form->setTranslator(new MockTranslator());
		$form->addSelect('translateFalse', 'label', ['a', 'b', 'c'])
			->setTranslate(FALSE)
			->setPrompt('Prompt');
		$form->addSelect('translateFalseEmptyPrompt', 'label', ['a', 'b', 'c'])
			->setTranslate(FALSE);
		$form->addSelect('translate', 'label', ['a', 'b', 'c'])
			->setTranslate(TRUE)
			->setPrompt('Prompt');
		$form->addSelect('translateEmptyPrompt', 'label', ['a', 'b', 'c'])
			->setTranslate(TRUE);

		// Translate false
		$form['translateFalse']->getControl();
		$this->assertSame([
			0 => 'Prompt'
		], MockTranslator::$toTranslate);
		MockTranslator::$toTranslate = [];

		// Translate false, empty prompt
		$form['translateFalseEmptyPrompt']->getControl();
		$this->assertSame([], MockTranslator::$toTranslate);
		MockTranslator::$toTranslate = [];

		// Translate
		$form['translate']->getControl();
		$this->assertSame([
			'Prompt', 'a', 'b', 'c'
		], MockTranslator::$toTranslate);
		MockTranslator::$toTranslate = [];

		// Translate, empty prompt
		$form['translateEmptyPrompt']->getControl();
		$this->assertSame([
			'a', 'b', 'c'
		], MockTranslator::$toTranslate);
		MockTranslator::$toTranslate = [];
	}

}

class MockTranslator implements \Nette\Localization\ITranslator {

	/** @var array */
	public static $toTranslate = [];

	public function translate($message, $count = NULL) {
		self::$toTranslate[] = $message;

		return $message;
	}

}
