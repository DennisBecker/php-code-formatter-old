<?php
Namespace PHP\CodeFormatter;

use PHP\CodeFormatter\Standards\StandardInterface;

class Formatter
{
	protected $tokenizer;
	
	protected $standard;
	
	public function __construct(Tokenizer $tokenizer, StandardInterface $standard)
	{
		$this->tokenizer = $tokenizer;
		$this->standard = $standard;
	}
	
	public function format($source)
	{
		$tokens = $this->tokenizer->tokenize($source);
		$output = '';
		
		foreach ($tokens as $token) {
			
			$methodName = $this->buildMethodName($token->getName());
			if (method_exists($this->standard, $methodName)) {
				$output .= $this->standard->$methodName($token);
			} else {
				var_dump($methodName);
				die();
			}
			
			var_dump($output);
		}
	}
	
	protected function buildMethodName($name)
	{
		$name = strtolower(substr($name, 2));
		$splittedName = explode('_', $name);
		
		$parts = count($splittedName);
		for ($i = 1; $i < $parts; $i++) {
			$splittedName[$i] = ucfirst($splittedName[$i]); 
		}
		
		
		return implode('', $splittedName);
	}
}