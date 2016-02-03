<?php


class RadioListTest extends \Codeception\TestCase\Test {

	public function testNoTranslate() {
		$form = new Form();

		$form->setTranslator(new RadioListMockTranslator());
		$form->addRadioList('translateFalse', 'label', ['a', 'b', 'c'])
			->setTranslate(FALSE);
		$form->addRadioList('translate', 'label', ['a', 'b', 'c'])
			->setTranslate(TRUE);

		// Translate false
		$form['translateFalse']->getControl();
		$this->assertSame([], RadioListMockTranslator::$toTranslate);
		RadioListMockTranslator::$toTranslate = [];

		// Translate
		$form['translate']->getControl();
		$this->assertSame([
			'a', 'b', 'c'
		], RadioListMockTranslator::$toTranslate);
		RadioListMockTranslator::$toTranslate = [];
	}

}

class RadioListMockTranslator implements \Nette\Localization\ITranslator {

	/** @var array */
	public static $toTranslate = [];

	public function translate($message, $count = NULL) {
		self::$toTranslate[] = $message;

		return $message;
	}

}