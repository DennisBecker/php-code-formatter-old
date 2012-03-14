<?php

namespace PHP\CodeFormatter\Decorators;

require_once 'PHPUnit\Framework\TestCase.php';
require_once 'src\PHP\CodeFormatter\Decorators\DecoratorInterface.php';
require_once 'src\PHP\CodeFormatter\Decorators\NewLine.php';

/**
 * NewLineBefore test case.
 */
class NewLineTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var NewLineAfter
	 */
	private $decorator;

	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		$this->decorator = $this->getMockForAbstractClass('PHP\CodeFormatter\Decorators\NewLine');
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

	public function testNewLineSequenceIsInitialUnixStyle() {	
		$newLineSequence = $this->decorator->getLineEndingSequence();
		
		$this->assertEquals("\n", $newLineSequence);
	}
	
	public function testMacOsNewLineSequenceConstant() {
		$this->assertEquals("\r", NewLine::MACOS_NEWLINE);
	}
	
	public function testUnixNewLineSequenceConstant() {
		$this->assertEquals("\n", NewLine::UNIX_NEWLINE);
	}
	
	public function testWindowsNewLineSequenceConstant() {
		$this->assertEquals("\r\n", NewLine::WINDOWS_NEWLINE);
	}
	
	public function testSetMacOsLineEndingChangesLineEndingSequence() {
		$this->decorator->setMacOsLineEnding();
		$newLineSequence = $this->decorator->getLineEndingSequence();
		
		$this->assertEquals("\r", $newLineSequence);
	}
	
	public function testSetUnixLineEndingChangesLineEndingSequence() {
		$this->decorator->setUnixLineEnding();
		$newLineSequence = $this->decorator->getLineEndingSequence();
		
		$this->assertEquals("\n", $newLineSequence);
	}
	
	public function testSetWindowsLineEndingChangesLineEndingSequence() {
		$this->decorator->setWindowsLineEnding();
		$newLineSequence = $this->decorator->getLineEndingSequence();
		
		$this->assertEquals("\r\n", $newLineSequence);
	}
}