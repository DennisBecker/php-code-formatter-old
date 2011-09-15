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
	
	protected $curlyBrackets = array(
		'T_CLASS',
		'T_INTERFACE',
		'T_CASE',
		'T_TRY',
		'T_ELSE',
		'T_FUNCTION_CLOSE_ROUND_BRACKET',
		'T_IF_CLOSE_ROUND_BRACKET',
		'T_ELSEIF_CLOSE_ROUND_BRACKET',
		'T_FOR_CLOSE_ROUND_BRACKET',
		'T_FOREACH_CLOSE_ROUND_BRACKET',
		'T_WHILE_CLOSE_ROUND_BRACKET',
		'T_CATCH_CLOSE_ROUND_BRACKET',
		'T_SWITCH_CLOSE_ROUND_BRACKET',
	);
	
	protected $roundBrackets = array(
		'T_FUNCTION',
		'T_IF',
		'T_ELSEIF',
		'T_FOR',
		'T_FOREACH',
		'T_WHILE',
		'T_CATCH',
		'T_SWITCH',
	);
	
//	protected $curlyAfterRoundBrackets = array(
//		'T_FUNCTION_CLOSE_ROUND_BRACKET',
//		'T_IF_CLOSE_ROUND_BRACKET',
//		'T_ELSEIF_CLOSE_ROUND_BRACKET',
//		'T_FOR_CLOSE_ROUND_BRACKET',
//		'T_FOREACH_CLOSE_ROUND_BRACKET',
//		'T_WHILE_CLOSE_ROUND_BRACKET',
//		'T_CATCH_CLOSE_ROUND_BRACKET',
//		'T_SWITCH_CLOSE_ROUND_BRACKET',
//	);
	
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
		$previousToken = null;
		
		foreach ($tokenizedSource as $key => $sourceToken) {
			
			if ($sourceToken[0] === T_WHITESPACE) {
				continue;
			}
			
			$parsedSourceToken = array();
			
			if (is_array($sourceToken)) {
				$parsedSourceToken = $sourceToken;
				$parsedSourceToken[3] = token_name($sourceToken[0]);
				
//				$this->addTokenOntoStack($parsedSourceToken[3]);
			} else {
				$parsedSourceToken = $this->addTokenData($sourceToken, $previousToken);
			}
			
			$tokenObject = new Token($parsedSourceToken[3], $parsedSourceToken[2], $parsedSourceToken[1], $previousToken);
			$tokenCollection[] = $previousToken = $tokenObject;
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
			case 'T_INTERFACE':
			case 'T_CASE':
			case 'T_TRY':
			case 'T_ELSE':
				array_unshift($this->curlyBracketStack, $tokenName);
				break;
			case 'T_CATCH':
			case 'T_FUNCTION':
			case 'T_IF':
			case 'T_ELSEIF':
			case 'T_SWITCH':
			case 'T_FOR':
			case 'T_FOREACH':
			case 'T_WHILE':
				array_unshift($this->roundBracketStack, $tokenName);
				break;
		}
	}
	
	/**
	 * Add data for non-PHP-tokens so that they can be used like normal tokens
	 * 
	 * @param string $sourceString
	 */
	protected function addTokenData($sourceString, Token $previousToken)
	{
		$tokenName = '';
		$controlTokenName = '';
		switch ($sourceString) {
			case '{':
//				if(!isset($this->curlyBracketStack[0])) {
//					$tokenName = 'T_OPEN_CURLY_BRACKET';
//				} else {
//					$tokenName = $this->curlyBracketStack[0].'_OPEN_CURLY_BRACKET';
//				}
				$tokenName = 'T_OPEN_CURLY_BRACKET';
				
				$controlTokenName = $this->findPreviousControlTokenForCurlyBracket($previousToken);
				if (null !== $controlTokenName) {
					$tokenName = str_replace('_CLOSE_ROUND_BRACKET', '', $controlTokenName);
					$tokenName .= '_OPEN_CURLY_BRACKET';
				}
				
				array_unshift($this->curlyBracketStack, $tokenName);
				break;
			case '}':
//				if(empty($this->curlyBracketStack)) {
//					$tokenName = 'T_CLOSE_CURLY_BRACKET';
//				} else {
//					$tokenName = array_shift($this->curlyBracketStack).'_CLOSE_CURLY_BRACKET';
//				}
				$tokenName = array_shift($this->curlyBracketStack);
//var_dump($tokenName);
				$tokenName = str_replace('_OPEN_', '_CLOSE_', $tokenName);
//				var_dump($tokenName);
				break;
			case '(':
				$tokenName = 'T_OPEN_ROUND_BRACKET';
				
				$controlTokenName = $this->findPreviousControlTokenForRoundBracket($previousToken);
//				var_dump($sourceString);
//				var_dump($previousToken->getPreviousToken()->getName());
//				var_dump($previousToken->getPreviousToken()->getContent());
//				var_dump($controlTokenName);
//				die();
				if (null !== $controlTokenName) {
					$tokenName = $controlTokenName . '_OPEN_ROUND_BRACKET';
				}
//				if (in_array($previousToken->getName(), $this->roundBrackets)) {
//					$tokenName = $previousToken->getName().'_OPEN_ROUND_BRACKET';
//				}
//				var_dump($sourceString);
//				var_dump($tokenName);
//				die();
				array_unshift($this->roundBracketStack, $tokenName);
				break;
			case ')':
				$tokenName = str_replace('_OPEN_', '_CLOSE_', array_shift($this->roundBracketStack));
//				var_dump($tokenName);
//				if (in_array($tokenName, $this->curlyBrackets)) {
//					array_unshift($this->curlyBracketStack, str_replace('_CLOSE_ROUND_BRACKET', '', $tokenName));
//				}
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
				$tokenName = 'T_MINUS';
				break;
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
			case '"':
				$tokenName = 'T_DOUBLE_QUOTES';
				break;
			case '?':
				$tokenName = 'T_QUESTION_MARK';
				break;
			case '&':
				$tokenName = 'T_AMPERSAND';
				break;
			case '@':
				$tokenName = 'T_AT';
				break;
			case '/':
				$tokenName = 'T_DIV';
				break;
			case '*':
				$tokenName = 'T_MULT';
				break;
			case '^':
				$tokenName = 'T_CARET';
				break;
			case '$':
				$tokenName = 'T_DOLLAR';
				break;
			case '|':
				$tokenName = 'T_PIPE';
				break;
			case '%':
				$tokenName = 'T_PERCENT';
				break;
			case '~':
				$tokenName = 'T_TILDE';
				break;
			case '`':
				$tokenName = 'T_BACKTICK';
				break;
			default:
				$count = count($this->tokenCollection);
				var_dump($this->tokenCollection[$count-3]);
				var_dump($this->tokenCollection[$count-2]);
				var_dump($this->tokenCollection[$count-1]);
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
	
	protected function findPreviousControlTokenForCurlyBracket(Token $token) {
		if (in_array($token->getName(), $this->curlyBrackets)) {
			return $token->getName();
		} elseif (false !== strpos($token->getName(), 'OPEN_CURLY')) {
			return null;
		} elseif (null !== $token->getPreviousToken()) {
			return $this->findPreviousControlTokenForCurlyBracket($token->getPreviousToken());
		} else {
			return null;
		}
	}
	
	protected function findPreviousControlTokenForRoundBracket(Token $token) {
		if (in_array($token->getName(), $this->roundBrackets)) {
			return $token->getName();
		} elseif (false !== strpos($token->getName(), 'OPEN_ROUND')) {
			return null;
		} elseif (false !== strpos($token->getName(), 'CLOSE_ROUND')) {
			return null;
		} elseif (null !== $token->getPreviousToken()) {
			return $this->findPreviousControlTokenForRoundBracket($token->getPreviousToken());
		} else {
			return null;
		}
	}
}