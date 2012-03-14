<?php
use PHP\CodeFormatter\Standards\Pear;
use PHP\CodeFormatter\Token;

class PearTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		
	}
	
	protected function tearDown()
	{
		
	}
	
	public function testOpenTagUsesLongPhpOpenTagAndhNewLine()
	{
		$pearStandard = new Pear();
		$token = new Token(T_OPEN_TAG, 'T_OPEN_TAG', '<?php');
		
		$this->assertEquals("<?php\n", $pearStandard->openTag($token));
	}
	
	public function testOpenTagUsesLongPhpOpenTagAndNewLineWithShortOpenTag()
	{
		$pearStandard = new Pear();
		$token = new Token(T_OPEN_TAG, 'T_OPEN_TAG', '<?');
		
		$this->assertEquals("<?php\n", $pearStandard->openTag($token));
	}
}