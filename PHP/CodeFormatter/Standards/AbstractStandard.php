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
 * Description.
 *
 * @package    PHP_CodeFormatter
 * @author     Dennis Becker
 * @copyright  2011 Dennis Becker
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       https://github.com/DennisBecker/php-code-formatter
 */
abstract class AbstractStandard
{	
	protected $newLineCharacter;
	protected $indentCharacter;
	protected $indentWidth;
	protected $currentIndent = 0;
	protected $newLineBefore = array();
	protected $newLineAfter = array();
	protected $emptyLineBefore = array();
	protected $emptyLineAfter = array();
	protected $spaceBefore = array();
	protected $spaceAfter = array();
	
	public function addEmptyLineBefore($name)
	{
		if (in_array($name, $this->emptyLineBefore)) {
			return true;
		}
		
		return false;
	}
	
	public function addEmptyLineAfter($name)
	{
		if (in_array($name, $this->emptyLineAfter)) {
			return true;
		}
		
		return false;
	}
	
	public function addNewLineBefore($name)
	{
		if (in_array($name, $this->newLineBefore)) {
			return true;
		}
		
		return false;
	}
	
	public function addNewLineAfter($name)
	{
		if (in_array($name, $this->newLineAfter)) {
			return true;
		}
		
		return false;
	}
	
	public function addSpaceBefore($name)
	{
		if (in_array($name, $this->spaceBefore)) {
			return true;
		}
		
		return false;
	}
	
	public function addSpaceAfter($name)
	{
		if (in_array($name, $this->spaceAfter)) {
			return true;
		}
		
		return false;
	}
	
	public function increaseThisLine($name)
	{
		if (in_array($name, $this->increaseThisLine)) {
			return true;
		}
		
		return false;
	}
	
	public function increaseNextLine($name)
	{
		if (in_array($name, $this->increaseNextLine)) {
			return true;
		}
		
		return false;
	}
	
	public function decreaseThisLine($name)
	{
		if (in_array($name, $this->decreaseThisLine)) {
			return true;
		}
		
		return false;
	}
	
	public function decreaseNextLine($name)
	{
		if (in_array($name, $this->decreaseNextLine)) {
			return true;
		}
		
		return false;
	}
		
	public function getIndentation()
	{
		return $this->currentIndent;
	}
	
	public function getIndentationCharacter()
	{
		return $this->indentCharacter;
	}
	
	public function getIndentationWidth()
	{
		return $this->indentWidth;
	}
	
	public function increaseIndentation()
	{
		$this->currentIndent++;
	}
	
	public function decreaseIndentation()
	{
		$this->currentIndent--;
	}
}