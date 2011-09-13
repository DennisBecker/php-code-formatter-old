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
 *   * Neither the name of Dennis Becker nor the names of his
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

use PHP\CodeFormatter\Token\ClassToken;
use PHP\CodeFormatter\Token\DocCommentToken;
use PHP\CodeFormatter\Token\OpenTagToken;
use PHP\CodeFormatter\SourceCodeLine;
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
		$this->standard->resetIndentation();
		$this->lines = array();
		$this->line = new SourceCodeLine($this->standard->getIndentationCharacter(),
			$this->standard->getIndentationWidth(), 0);
		
		foreach ($tokens as $token) {
			$tokenName = $token->getName();
			$methodName = $this->buildMethodName($tokenName);
			
			if ($this->standard->addEmptyLineBefore($tokenName)) {
				$this->line->addContent(null);
				$this->newLine();
			}
			
			if ($this->standard->addNewLineBefore($tokenName)) {
				$this->newLine();
			}
			
			if ($this->standard->increaseThisLine($tokenName)) {
				$this->standard->increaseIndentation();
				$this->line->setIndentation($this->standard->getIndentation());
			}
			
			if ($this->standard->decreaseThisLine($tokenName)) {
				$this->standard->decreaseIndentation();
				$this->line->setIndentation($this->standard->getIndentation());
			}
			
			if ($this->standard->addSpaceBefore($tokenName)) {
				$this->line->addContent(' ');
			}
			
			$this->line->addContent($this->standard->$methodName($token));
			
			if ($this->standard->increaseNextLine($tokenName)) {
				$this->standard->increaseIndentation();
			}
			
			if ($this->standard->decreaseNextLine($tokenName)) {
				$this->standard->decreaseIndentation();
			}
			
			if ($this->standard->addSpaceAfter($tokenName)) {
				$this->line->addContent(' ');
			}
			
			if ($this->standard->addNewLineAfter($tokenName)) {
				$this->newLine();
			}
			
			if ($this->standard->addEmptyLineAfter($tokenName)) {
				$this->newLine();
				$this->line->addContent(null);
			}
			
			$this->addDocComment($token);
		}
		
		unset($this->line);

		$output = '';
		foreach ($this->lines as $line) {
			$output .= $line->getLine();
		}
		
		return $output;
	}
	
	protected function addDocComment($token)
	{
		if($token instanceof DocCommentToken) {
			if($token instanceof OpenTagToken) {
				$this->line->addContent($token->getDocComment());
				$this->newLine();
				$this->newLine();
			} else {
				$this->newLine();
				$this->line->addContent($token->getDocComment());
				
				$key = count($this->lines)-1;
				$this->newLine();
				$tmpLine = $this->line;
				$this->line = $this->lines[$key]; 
				$this->lines[$key] = $tmpLine;
			}
		}
	}
	
	/**
	 * Add current line to the lines collection and create a new
	 * instance of SourceCodeLine
	 */
	protected function newLine()
	{
		$this->lines[] = $this->line;
		$this->line = new SourceCodeLine($this->standard->getIndentationCharacter(),
			$this->standard->getIndentationWidth(), $this->standard->getIndentation());
	}
	
	/**
	 * Parses token name and returns a camelCased method name
	 * 
	 * @param string $name
	 * @return string
	 */
	protected function buildMethodName($name)
	{
		$splittedName = explode('_', strtolower($name));
		
		return implode('', $splittedName);
	}
}