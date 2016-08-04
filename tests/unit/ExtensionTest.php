<?php

class ExtensionTest extends \Codeception\TestCase\Test {

	/** @var \Nette\DI\Compiler */
	private $compiler;

	protected function setUp() {
		$this->compiler = $compiler = new \Nette\DI\Compiler();
		$compiler->addExtension('controls', new \WebChemistry\Forms\DI\FormControlsExtension());
	}

	public function testCompile() {
		$this->compile();
	}

	public function testEval() {
		eval($this->compile());
		$this->assertTrue(class_exists('Container'));
		$container = new Container();

		$this->assertTrue(method_exists($container, 'initialize'));
		$container->initialize();
	}

	public function testOptions() {
		$this->compiler->setClassName('Container_Controls');
		$this->compiler->addConfig([
			'controls' => [
				'date' => [
					'format' => 'foo'
				],
				'translations' => [
					'enable' => TRUE,
					'date' => 'bar'
				]
			]
		]);
		$this->compile();
		eval($this->compile());
		$container = new Container_Controls();

		$count = count(\Nette\Forms\Validator::$messages);
		$container->initialize();

		$this->assertNotSame($count, count(\Nette\Forms\Validator::$messages));
		$this->assertSame('bar', \Nette\Forms\Validator::$messages[\WebChemistry\Forms\Controls\Date::VALID]);
		$this->assertSame('foo', \WebChemistry\Forms\Controls\Date::$dateFormat);
	}

	protected function compile() {
		$result = $this->compiler->compile();
		if (is_array($result)) {
			return implode("\n\n\n", $result);
		}

		return $result;
	}

}
