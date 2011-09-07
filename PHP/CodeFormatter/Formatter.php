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

use PHP\CodeFormatter\Tokenizer;
use PHP\CodeFormatter\Standards\AbstractStandard;

/**
 * Handles tokens and uses given coding standard class to format the source code
 *
 * @package    PHP_CodeFormatter
 * @author     Dennis Becker
 * @copyright  2011 Dennis Becker
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       https://github.com/DennisBecker/php-code-formatter
 */
class Formatter
{
	/**
	 * @var Tokenizer
	 */
	protected $tokenizer;
	
	/**
	 * @var AbstractStandard
	 */
	protected $standard;
	
	/**
	 * Create new instance of Formatter
	 * 
	 * @param Tokenizer $tokenizer
	 * @param AbstractStandard $standard
	 */
	public function __construct(Tokenizer $tokenizer, AbstractStandard $standard)
	{
		$this->tokenizer = $tokenizer;
		$this->standard = $standard;
	}
	
	/**
	 * Run formatting rules of the used coding standard on given source code
	 * 
	 * @param string $source
	 */
	public function format($source)
	{
		$tokens = $this->tokenizer->tokenize($source);
		$output = '';
		
//		$lines = array();
//		$indentLine = false;
//		$indentNextLine = false;
//		$usedKeys = array();
//		foreach ($tokens as $key => $token) {
//			if(in_array($key, $usedKeys)) {
//				continue;
//			}
//			
//			if ($indentNextLine) {
//				$indentLine = true;
//				$indentNextLine = false;
//			}
//			
//			$lineCount = count($lines);
//			$lines[$lineCount] = '';
//			switch ($token->getName()) {
//				case 'T_OPEN_TAG':
//					$lines[$lineCount] .= $this->standard->getOpenTag();
//					break;
//				case 'T_CLASS':
//					$usedKeys[] = $key+1;
//					$lines[$lineCount] .= $token->getContent() . " " . $tokens[$key+1]->getContent();
//					break;
//				case 'T_CLASS_OPEN_CURLY_BRACKET':
//					if($this->standard->isClassOpenCurlyBracketOnSameLine()) {
//						$lines[$lineCount-1] .= " " . $token->getContent();
//						unset($lines[$lineCount]);
//					} else {
//						$lines[$lineCount] .= $token->getContent();
//					}
//					
//					$this->standard->increaseIndent();
//					$indentNextLine = true;
//					break;
//				case 'T_PUBLIC':
//					$lines[$lineCount] .= $token->getContent() . " ";
//					$i = 1;
//					$addStrings = array();
//					while ($tokens[$key+$i]->getContent() != '(') {
//						$usedKeys[] = $key+$i;
//						$addStrings[] = $tokens[$key+$i]->getContent();
//						++$i;
//					}
//					$lines[$lineCount] .= implode(' ', $addStrings);
////					var_dump($tokens[$key+$i]->getContent());
////					$lines[$lineCount] .= $tokens[$key+$i]->getContent();
////					$usedKeys[] = $key+$i;
//					
//					while ($tokens[$key+$i]->getContent() != '{') {
//						$usedKeys[] = $key+$i;
//						$lines[$lineCount] .= $tokens[$key+$i]->getContent();
//						if ($tokens[$key+$i]->getContent() == ',') {
//							$lines[$lineCount] .= " ";
//						}
//						++$i;
//					}
//					break;
//				case 'T_FUNCTION_OPEN_CURLY_BRACKET':
//					if($this->standard->isFunctionOpenCurlyBracketOnSameLine()) {
//						$lines[$lineCount-1] .= " " . $token->getContent();
//						unset($lines[$lineCount]);
//					} else {
//						$lines[$lineCount] .= $token->getContent();
//					}
//					
//					$this->standard->increaseIndent();
//					break;
//				case 'T_IF':
//					$lines[$lineCount] .= $token->getContent();
//					break;
//				default:
//					var_dump($token);
//					var_dump($lines);
//					die();
//			}
//			
//			if($indentLine && $lines[$lineCount] != '') {
//				$lines[$lineCount] = $this->standard->addIndent() . $lines[$lineCount];
//			}
//		}
//		
//		var_dump($lines);
//		die();
		
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
	
	/**
	 * Parses token name and returns a camelCased method name
	 * 
	 * @param string $name
	 * @return string
	 */
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