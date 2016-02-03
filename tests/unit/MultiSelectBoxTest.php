<?php

class MultiSelectBoxTest extends \Codeception\TestCase\Test {

	public function testNoTranslate() {
		$form = new Form();

		$form->setTranslator(new MultiSelectMockTranslator());
		$form->addMultiSelect('translateFalse', 'label', ['a', 'b', 'c'])
			->setTranslate(FALSE);
		$form->addMultiSelect('translate', 'label', ['a', 'b', 'c'])
			->setTranslate(TRUE);

		// Translate false
		$form['translateFalse']->getLabel();
		$form['translateFalse']->getControl();
		$this->assertSame([
			'label'
		], MultiSelectMockTranslator::$toTranslate);
		MultiSelectMockTranslator::$toTranslate = [];

		// Translate
		$form['translate']->getLabel();
		$form['translate']->getControl();
		$this->assertSame([
			'label', 'a', 'b', 'c'
		], MultiSelectMockTranslator::$toTranslate);
		MultiSelectMockTranslator::$toTranslate = [];
	}

}

class MultiSelectMockTranslator implements \Nette\Localization\ITranslator {

	/** @var array */
	public static $toTranslate = [];

	public function translate($message, $count = NULL) {
		self::$toTranslate[] = $message;

		return $message;
	}

}
