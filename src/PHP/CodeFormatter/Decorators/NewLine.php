<?php

namespace PHP\CodeFormatter\Decorators;

abstract class NewLine implements DecoratorInterface
{
	const MACOS_NEWLINE = "\r";
	const UNIX_NEWLINE = "\n";
	const WINDOWS_NEWLINE = "\r\n";
	
	protected $newLineSequence = self::UNIX_NEWLINE;
	
	public function getLineEndingSequence()
	{
		return $this->newLineSequence;
	}
	
	public function setMacOsLineEnding()
	{
		$this->newLineSequence = self::MACOS_NEWLINE;
	}
	
	public function setUnixLineEnding()
	{
		$this->newLineSequence = self::UNIX_NEWLINE;
	}
	
	public function setWindowsLineEnding()
	{
		$this->newLineSequence = self::WINDOWS_NEWLINE;
	}
}