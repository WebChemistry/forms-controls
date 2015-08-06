<?php

class LiveValidationTest extends \Codeception\TestCase\Test {

	protected function _before() {
	}

	protected function _after() {
	}

	public function testRender() {
		/** @var \Nette\Application\IPresenterFactory $presenterFactory */
		$presenterFactory = E::getByType('Nette\Application\IPresenterFactory');
		$presenter = $presenterFactory->createPresenter('LiveValidation');
		$presenter->autoCanonicalize = FALSE;

		$presenter->run(new \Nette\Application\Request('LiveValidation', 'GET'));

		/** @var \Form $form */
		$form = $presenter['form'];

		$this->assertStringEqualsFile(E::dumpedFile('liveValidationRender'), (string) $form);
	}
}