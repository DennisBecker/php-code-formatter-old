<?php
Namespace PHP\CodeFormatter\Standards;

use PHP\CodeFormatter\Token;

class Pear implements StandardInterface
{
	protected $newLineCharacter = "\n";
	protected $indentCharacter = ' ';
	protected $indentWidth = 4;
	
	public function setNewLineCharacter($string)
	{
		$this->newLineCharacter = $string;
	}
	
	public function openTag(Token $token) {
		$output = $token->getContent();
		$output .= $this->addNewLine();
		
		return $output;
	}
	
	public function addNewLine()
	{
		return $this->newLineCharacter;
	}
	
	public function increaseIndent()
	{
		
	}
}