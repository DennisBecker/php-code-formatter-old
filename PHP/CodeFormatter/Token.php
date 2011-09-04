<?php
Namespace PHP\CodeFormatter;

class Token
{
	protected $token;
	protected $name;
	protected $content;
	protected $previousToken;
	
	public function __construct($token, $name, $content, $previousToken = null)
	{
		$this->token = $token;
		$this->name = $name;
		$this->content = $content;
		$this->previousToken = $previousToken;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getContent()
	{
		return $this->content;
	}
}