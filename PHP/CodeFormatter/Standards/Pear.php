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

namespace PHP\CodeFormatter\Standards;

use PHP\CodeFormatter\Token;

/**
 * PEAR Coding Standard
 *
 * @package    PHP_CodeFormatter
 * @author     Dennis Becker
 * @copyright  2011 Dennis Becker
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       https://github.com/DennisBecker/php-code-formatter
 */
class Pear extends AbstractStandard {
	
	protected $newLineCharacter = "\n";
	protected $indentCharacter = ' ';
	protected $indentWidth = 4;
	
	public function getOpenTag()
	{
		return '<?php';
	}
	
	public function tOpenTag(Token $token)
	{
		return '<?php' . $this->addNewLineAndIndent();
	}
	
	public function isClassOpenCurlyBracketOnSameLine()
	{
		return true;
	}
	
	public function isFunctionOpenCurlyBracketOnSameLine()
	{
		return true;
	}
	
	public function tClass(Token $token)
	{
		return $token->getContent();
	}
	
	public function tClassOpenCurlyBracket(Token $token)
	{
		$this->increaseIndent();
		return " " . $token->getContent() . $this->addNewLineAndIndent();
	}
	
	public function tClassCloseCurlyBracket(Token $token)
	{
		$this->decreaseIndent();
		return $this->addIndent() . $token->getContent() . $this->addNewLineAndIndent();
	}
	
	public function tString(Token $token)
	{
		return " " . $token->getContent();
	}
	
	public function tPublic(Token $token)
	{
		return $token->getContent() . " ";
	}
	
	public function tFunction(Token $token)
	{
		return $token->getContent();
	}
	
	public function tStringOpenRoundBracket(Token $token)
	{
		return $token->getContent();
	}
	
	public function tStringCloseRoundBracket(Token $token)
	{
		return $token->getContent(); 
	}
	
	public function tVariable(Token $token)
	{
		return $token->getContent();
	}
	
	public function tComma(Token $token)
	{
		return $token->getContent() . " ";
	}
	
	public function tFunctionOpenCurlyBracket(Token $token)
	{
		$this->increaseIndent();
		return $token->getContent() . $this->addNewLineAndIndent();
	}
	
	public function tFunctionCloseCurlyBracket(Token $token)
	{
		$this->decreaseIndent();
		return $token->getContent() . $this->addNewLineAndIndent();
	}
	
	public function tIf(Token $token)
	{
		return $token->getContent() . " ";
	}
	
	public function tIfOpenRoundBracket(Token $token)
	{
		return $token->getContent();
	}
	
	public function tIfCloseRoundBracket(Token $token)
	{
		return $token->getContent(); 
	}
	
	public function tIfOpenCurlyBracket(Token $token)
	{
		$this->increaseIndent();
		return " " . $token->getContent() . $this->addNewLineAndIndent();
	}
	
	public function tIfCloseCurlyBracket(Token $token)
	{
		$this->decreaseIndent();
		return $token->getContent() . $this->addNewLineAndIndent();
	}
	
	public function tExcalmationMark(Token $token)
	{
		return $token->getContent(); 
	}
	
	public function tBooleanOr(Token $token)
	{
		return " " . $token->getContent() . " "; 
	}
	
	public function tBooleanAnd(Token $token)
	{
		return " " . $token->getContent() . " "; 
	}
	
	public function tThrow(Token $token)
	{
		return $token->getContent() . " ";
	}
	
	public function tNew(Token $token)
	{
		return $token->getContent();
	}
	
	public function tConstantEncapsedString(Token $token)
	{
		return $token->getContent();
	}
	
	public function tSemicolon(Token $token)
	{
		return $token->getContent() . $this->addNewLineAndIndent();
	}
	
	public function tReturn(Token $token)
	{
		return $token->getContent() . " ";
	}
	
	public function tPlus(Token $token)
	{
		return $token->getContent();
	}
}