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

		$tags->setDefaultValue([
			0 => 'first',
			1 => 'second'
		]);

		$this->assertSame([
			0 => 'first',
			1 => 'second'
		], $tags->getValue());

		$tags->setValue('first,    second     ,third');

		$this->assertSame([
			0 => 'first',
			1 => 'second',
			2 => 'third'
		], $tags->getValue());

		$tags->setValue('first, second, first');
		$this->assertSame([
			'first', 'second'
		], $tags->getValue());
	}

	public function testRender() {
		$form = new Form;

		$tags = $form->addTags('tags')
			->setDefaultValue(NULL);

		$this->assertStringEqualsFile(__DIR__ . '/expected/tagsNull.dmp', $tags->getControl());

		$tags = $form->addTags('tagsTwo')
			->setDefaultValue(['tag', 'one', 'two']);

		$this->assertStringEqualsFile(__DIR__ . '/expected/tagsRender.dmp', $tags->getControl());
	}
}
