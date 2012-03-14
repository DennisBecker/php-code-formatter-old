<?php

namespace PHP\CodeFormatter\Decorators;

require_once 'PHPUnit\Framework\TestCase.php';
require_once 'src\PHP\CodeFormatter\Decorators\DecoratorInterface.php';
require_once 'src\PHP\CodeFormatter\Decorators\NewLine.php';
require_once 'src\PHP\CodeFormatter\Decorators\NewLineBefore.php';


/**
 * NewLineBefore test case.
 */
class NewLineBeforeTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var NewLineBefore
	 */
	private $decorator;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		$this->decorator = new NewLineBefore();
	}

	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
		$this->decorator = null;
	}
	
	/**
	 * Tests DecoratorInterface implementation
	 */
	public function testInterface() {
		$this->assertInstanceOf('PHP\CodeFormatter\Decorators\DecoratorInterface', $this->decorator);
	}
	
	/**
	 * Tests NewLine extending
	 */
	public function testDecoratorExtendsAbstractClassNewLine() {
		$this->assertInstanceOf('PHP\CodeFormatter\Decorators\NewLine', $this->decorator);
	}

	/**
	 * Tests render()
	 */
	public function testRender() {	
		$output = $this->decorator->render("foo");
		
		$this->assertEquals("\nfoo", $output);
	}
}