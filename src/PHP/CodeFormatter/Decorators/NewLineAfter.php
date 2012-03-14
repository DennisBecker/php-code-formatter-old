<?php
namespace PHP\CodeFormatter\Decorators;

class NewLineAfter extends NewLine
{
	public function render($string)
	{
		return $string . $this->newLineSequence;
	}
}