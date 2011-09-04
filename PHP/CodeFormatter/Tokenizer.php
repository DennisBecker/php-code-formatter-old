<?php
Namespace PHP\CodeFormatter;

class Tokenizer
{
	public function tokenize($source) {
		$tokenizedSource = token_get_all($source);
		
		$tokenCollection = array();
		foreach ($tokenizedSource as $key => $sourceToken) {
			if($sourceToken[0] === T_WHITESPACE) {
				continue;
			}
				
			if(is_array($sourceToken)) {
				$sourceToken = array_map('trim', $sourceToken);
				$sourceToken[3] = token_name($sourceToken[0]);
			} else {
				$sourceToken = $this->addTokenData($sourceToken);
			}
			$previousToken = null;
			$tokenCount = count($tokenCollection);
			if($tokenCount > 0) {
				$previousToken = $tokenCollection[$tokenCount-1];
			}
			
			$tokenCollection[] = new Token($sourceToken[0], $sourceToken[3], $sourceToken[1]);
		}
//		var_dump($tokenCollection);
		return $tokenCollection;
	}
	
	protected function addTokenData($string)
	{
		return array(
			'',
			$string,
			'',
			'',
		);
	}
}