<?php


class CheckboxListTest extends \Codeception\TestCase\Test {

    public function testNoTranslate() {
        $form = new Form();

        $form->setTranslator(new CheckboxListMockTranslator());
        $form->addCheckboxList('translateFalse', 'label', ['a', 'b', 'c'])
            ->setTranslate(FALSE);
        $form->addCheckboxList('translate', 'label', ['a', 'b', 'c'])
            ->setTranslate(TRUE);

        // Translate false
        $form['translateFalse']->getControl();
        $this->assertSame([], CheckboxListMockTranslator::$toTranslate);
        CheckboxListMockTranslator::$toTranslate = [];

        // Translate
        $form['translate']->getControl();
        $this->assertSame([
            'a', 'b', 'c'
        ], CheckboxListMockTranslator::$toTranslate);
        CheckboxListMockTranslator::$toTranslate = [];
    }

}

class CheckboxListMockTranslator implements \Nette\Localization\ITranslator {

    /** @var array */
    public static $toTranslate = [];

    public function translate($message, $count = NULL) {
        self::$toTranslate[] = $message;

        return $message;
    }

}