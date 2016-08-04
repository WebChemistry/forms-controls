<?php

class EditorTest extends \PHPUnit_Framework_TestCase {

	public function testValues() {
		$form = new Form;

		$editor = $form->addEditor('editor');

		$editor->setValue(htmlentities('Příliš žluťoučký kůň úpěl ďábelské ódy'));

		$this->assertNotSame(htmlentities('Příliš žluťoučký kůň úpěl ďábelské ódy'), $editor->getValue());
		$this->assertSame('Příliš žluťoučký kůň úpěl ďábelské ódy', $editor->getValue());
	}
}
