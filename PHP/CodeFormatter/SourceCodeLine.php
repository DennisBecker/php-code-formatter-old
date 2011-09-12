<?php

namespace PHP\CodeFormatter;

class SourceCodeLine
{
	private $content;
	private $indentationCharacter;
	private $indentationWidth;
	private $lineIndentation;
	
	public function __construct($indentationCharacter, $indentationWidth, $lineIndentation)
	{
		$this->indentationCharacter = $indentationCharacter;
		$this->indentationWidth = $indentationWidth;
		$this->lineIndentation = $lineIndentation;
	}
	
	public function setIndentation($lineIndentation)
	{
		$this->lineIndentation = $lineIndentation;
	}
	
	public function addContent($content)
	{
		$this->content[] = $content;
	}
	
	public function getLine()
	{
		$indentation = '';
		
		for ($i = 0; $i < $this->lineIndentation; $i++) {
			for ($j = 0; $j < $this->indentationWidth; $j++) {
				$indentation .= $this->indentationCharacter;
			}
		}
		
		if (empty($this->content)) {
			return $indentation . "\n";
		}
		
		return $indentation . implode('', $this->content) . "\n";
	}
}