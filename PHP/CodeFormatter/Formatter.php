<?php
Namespace PHP\CodeFormatter;

use PHP\CodeFormatter\Standards\AbstractStandard;

class Formatter
{
	protected $tokenizer;
	
	protected $standard;
	
	public function __construct(Tokenizer $tokenizer, AbstractStandard $standard)
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
				echo "Unimplemented method '$methodName'\n";
				var_dump($token);
				die();
			}
		}
		
		var_dump($output);
	}
	
	protected function buildMethodName($name)
	{
		$name = strtolower($name);
		$splittedName = explode('_', $name);
		
		$parts = count($splittedName);
		for ($i = 1; $i < $parts; $i++) {
			$splittedName[$i] = ucfirst($splittedName[$i]); 
		}
		
		
		return implode('', $splittedName);
	}
}