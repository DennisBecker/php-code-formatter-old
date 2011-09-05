<?php
namespace PHP\CodeFormatter;

class Tokenizer
{
	protected $curlyBracketStack = array();
	protected $roundBracketStack = array();
	
	public function tokenize($source) {
		$tokenizedSource = token_get_all($source);
		
		$tokenCollection = array();
		
		foreach ($tokenizedSource as $key => $sourceToken) {
			if ($sourceToken[0] === T_WHITESPACE) {
				continue;
			}
			
			$previousToken = null;
			$parsedSourceToken = array();
			$tokenCount = count($tokenCollection);
			if($tokenCount > 0) {
				$previousToken = $tokenCollection[$tokenCount-1];
			}
			
			if(is_array($sourceToken)) {
				$parsedSourceToken = array_map('trim', $sourceToken);
				$parsedSourceToken[3] = token_name($sourceToken[0]);
				switch ($parsedSourceToken[3]) {
					case 'T_CLASS':
						array_unshift($this->curlyBracketStack, $parsedSourceToken[3]);
						break;
					case 'T_ARRAY':
					case 'T_STRING':
						array_unshift($this->roundBracketStack, $parsedSourceToken[3]);
						break;
					case 'T_FUNCTION':
					case 'T_IF':
						array_unshift($this->roundBracketStack, $parsedSourceToken[3]);
						array_unshift($this->curlyBracketStack, $parsedSourceToken[3]);
						break;
				}
			} else {
				$parsedSourceToken = $this->addTokenData($sourceToken, $previousToken);
			}
			
			$tokenCollection[] = new Token($parsedSourceToken[0], $parsedSourceToken[3], $parsedSourceToken[1], $previousToken);
		}
		
		return $tokenCollection;
	}
	
	protected function addTokenData($string, Token $previousToken)
	{
		$result = array();
		switch ($string) {
			case '{':
				$result = $this->curlyOpenBracket($string, $previousToken);
				break;
			case '}':
				$result = array(
					'0',
					$string,
					'',
					array_shift($this->curlyBracketStack).'_CLOSE_CURLY_BRACKET',
				);
				break;
			case '(':
				$result = $this->roundOpenBracket($string, $previousToken);
				break;
			case ')':
				$result = array(
					'0',
					$string,
					'',
					array_shift($this->roundBracketStack).'_CLOSE_ROUND_BRACKET',
				);
				break;
			case ',':
				$result = array(
					'0',
					$string,
					'',
					'T_COMMA',
				);
				break;
			case '!':
				$result = array(
					'0',
					$string,
					'',
					'T_EXCALMATION_MARK',
				);
				break;
			case ';':
				$result = array(
					'0',
					$string,
					'',
					'T_SEMICOLON',
				);
				break;
			case '+':
				$result = array(
					'0',
					$string,
					'',
					'T_PLUS',
				);
				break;
			default:
				var_dump($string);
				var_dump($previousToken->getPreviousToken()->getContent());
				die("\nMissing behaviour\n");
		}
		
		return $result;
	}
	
	protected function curlyOpenBracket($string, Token $previousToken)
	{
		return array(
			'0',
			$string,
			'',
			$this->curlyBracketStack[0].'_OPEN_CURLY_BRACKET',
		);
	}
	
	protected function roundOpenBracket($string, Token $previousToken)
	{
		if(!isset($this->roundBracketStack[0])) {
			var_dump($previousToken->getContent());
			var_dump($string);
			die();
		}
		return array(
			'0',
			$string,
			'',
			$this->roundBracketStack[0].'_OPEN_ROUND_BRACKET',
		);
	}
}