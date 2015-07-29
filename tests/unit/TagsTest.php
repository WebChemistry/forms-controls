<?php

class TagsTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
	}

	protected function tearDown() {
	}

	public function testValues() {
		$form = new Form;

		$tags = $form->addTags('tags')
					 ->setDefaultValue(NULL);

		$this->assertInstanceOf('WebChemistry\Forms\Controls\Tags', $tags);
		$this->assertNull($tags->getValue());

		$tags->setDefaultValue(array(
			0 => 'first',
			1 => 'second'
		));

		$this->assertSame(array(
			0 => 'first',
			1 => 'second'
		), $tags->getValue());

		$tags->setValue('first,    second     ,third');

		$this->assertSame(array(
			0 => 'first',
			1 => 'second',
			2 => 'third'
		), $tags->getValue());
	}

	public function testRender() {
		$form = new Form;

		$tags = $form->addTags('tags')
					 ->setDefaultValue(NULL);

		$this->assertStringEqualsFile(E::dumpedFile('tagsNull'), $tags->getControl());

		$tags = $form->addTags('tagsTwo')
					 ->setDefaultValue(array('tag', 'one', 'two'));

		$this->assertStringEqualsFile(E::dumpedFile('tagsRender'), $tags->getControl());
	}
}
