<?php
namespace PHP\CodeFormatter\Decorators;

class NewLineBefore extends NewLine
{
	public function render($string)
	{
		return $this->newLineSequence . $string;
	}
}