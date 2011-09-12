<?php
/**
 * PHP_CodeFormatter
 *
 * Copyright (c) 2011, Dennis Becker.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHP_CodeFormatter
 * @author     Dennis Becker
 * @copyright  2011 Dennis Becker
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       https://github.com/DennisBecker/php-code-formatter
 */

namespace PHP\CodeFormatter;

/**
 * Tokenizes php source code
 * 
 * Tokenizer class uses token_get_all to tokenize PHP source code and creates
 * token like data for non-default tokens.
 *
 * @package    PHP_CodeFormatter
 * @author     Dennis Becker
 * @copyright  2011 Dennis Becker
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       https://github.com/DennisBecker/php-code-formatter
 */
class Tokenizer
{
	/**
	 * Stack of token names to match curly brackets
	 * 
	 * @var array
	 */
	protected $curlyBracketStack = array();
	
	/**
	 * Stack of token names to match round brackets
	 * 
	 * @var array
	 */
	protected $roundBracketStack = array();
	
	/**
	 * Base tokenizing method which analyzes the given source code
	 * 
	 * @param string $source
	 */
	public function tokenize($source)
	{
		$tokenCollection = array();
		$this->curlyBracketStack = array();
		$this->roundBracketStack = array();
		$tokenizedSource = token_get_all($source);
		
		foreach ($tokenizedSource as $key => $sourceToken) {
			
			if ($sourceToken[0] === T_WHITESPACE) {
				continue;
			}
			
			$parsedSourceToken = array();
			
			if (is_array($sourceToken)) {
				$parsedSourceToken = $sourceToken;
				$parsedSourceToken[3] = token_name($sourceToken[0]);
				
				$this->addTokenOntoStack($parsedSourceToken[3]);
			} else {
				$parsedSourceToken = $this->addTokenData($sourceToken);
			}
			
			$tokenCollection[] = new Token($parsedSourceToken[3], $parsedSourceToken[1]);
			$this->tokenCollection = $tokenCollection;
		}
		
		return $tokenCollection;
	}
	
	/**
	 * Check if token must be added onto a stack for curly or round brackets
	 *
	 * @param string $tokenName
	 */
	protected function addTokenOntoStack($tokenName)
	{
		switch ($tokenName) {
			case 'T_CLASS':
			case 'T_ELSE':
			case 'T_CASE':
			case 'T_TRY':
				array_unshift($this->curlyBracketStack, $tokenName);
				break;
			case 'T_ARRAY':
			case 'T_STRING':
				array_unshift($this->roundBracketStack, $tokenName);
				break;
			case 'T_FUNCTION':
			case 'T_IF':
			case 'T_FOR':
			case 'T_FOREACH':
			case 'T_SWITCH':
			case 'T_CATCH':
				array_unshift($this->roundBracketStack, $tokenName);
				array_unshift($this->curlyBracketStack, $tokenName);
				break;
		}
	}
	
	/**
	 * Add data for non-PHP-tokens so that they can be used like normal tokens
	 * 
	 * @param string $sourceString
	 */
	protected function addTokenData($sourceString)
	{
		$tokenName = '';
		switch ($sourceString) {
			case '{':
				$tokenName = $this->curlyBracketStack[0].'_OPEN_CURLY_BRACKET';
				break;
			case '}':
				if(empty($this->curlyBracketStack)) {
					var_dump($sourceString);
					var_dump(count($this->tokenCollection));
					var_dump($this->tokenCollection[201]);
					die();
				}
				$tokenName = array_shift($this->curlyBracketStack).'_CLOSE_CURLY_BRACKET';
				break;
			case '(':
				$tokenName = $this->roundBracketStack[0].'_OPEN_ROUND_BRACKET';
				break;
			case ')':
				$tokenName = array_shift($this->roundBracketStack).'_CLOSE_ROUND_BRACKET';
				break;
			case ',':
				$tokenName = 'T_COMMA';
				break;
			case '!':
				$tokenName = 'T_EXCALMATION_MARK';
				break;
			case '.':
				$tokenName = 'T_DOT';
				break;
			case ':':
				$tokenName = 'T_COLON';
				break;
			case ';':
				$tokenName = 'T_SEMICOLON';
				break;
			case '+':
				$tokenName = 'T_PLUS';
				break;
			case '-':
			case '>':
				$tokenName = 'T_GT';
				break;
			case '<':
				$tokenName = 'T_LT';
				break;
			case '=':
				$tokenName = 'T_EQUAL';
				break;
			case '[':
				$tokenName = 'T_OPEN_SQUARE_BRACKET';
				break;
			case ']':
				$tokenName = 'T_CLOSE_SQUARE_BRACKET';
				break;
			default:
				var_dump($sourceString);
				die("\nMissing behaviour\n");
		}
		
		return array(
			'0',
			$sourceString,
			'',
			$tokenName,
		);
	}
}