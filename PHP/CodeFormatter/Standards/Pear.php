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
	protected $newLineBefore = array();
	protected $newLineAfter = array(
		'T_OPEN_TAG',
		'T_CLASS_OPEN_CURLY_BRACKET',
		'T_CLASS_CLOSE_CURLY_BRACKET',
		'T_FUNCTION_OPEN_CURLY_BRACKET',
		'T_FUNCTION_CLOSE_CURLY_BRACKET',
		'T_IF_OPEN_CURLY_BRACKET',
		'T_IF_CLOSE_CURLY_BRACKET',
		'T_FOR_OPEN_CURLY_BRACKET',
		'T_FOR_CLOSE_CURLY_BRACKET',
		'T_FOREACH_OPEN_CURLY_BRACKET',
		'T_FOREACH_CLOSE_CURLY_BRACKET',
		'T_TRY_OPEN_CURLY_BRACKET',
		'T_CATCH_OPEN_CURLY_BRACKET',
		'T_CATCH_CLOSE_CURLY_BRACKET',
		'T_SEMICOLON',
		'T_DOC_COMMENT',
	);
	protected $emptyLineBefore = array(
		'T_DOC_COMMENT',
	);
	protected $emptyLineAfter = array(
		'T_FUNCTION_CLOSE_CURLY_BRACKET',
	);
	protected $spaceBefore = array(
		'T_CLASS_OPEN_CURLY_BRACKET',
		'T_FUNCTION_OPEN_CURLY_BRACKET',
		'T_IF_OPEN_CURLY_BRACKET',
		'T_FOR_OPEN_CURLY_BRACKET',
		'T_FOREACH_OPEN_CURLY_BRACKET',
		'T_PLUS',
		'T_MINUS',
		'T_EQUAL',
		'T_DOT',
		'T_CATCH',
		'T_AS',
	);
	protected $spaceAfter = array(
		'T_CLASS',
		'T_PUBLIC',
		'T_PROTECTED',
		'T_PRIVATE',
		'T_FUNCTION',
		'T_COMMA',
		'T_IF',
		'T_FOR',
		'T_FOREACH',
		'T_THROW',
		'T_NEW',
		'T_RETURN',
		'T_PLUS',
		'T_MINUS',
		'T_NAMESPACE',
		'T_USE',
		'T_EQUAL',
		'T_DOT',
		'T_STATIC',
		'T_TRY',
		'T_CATCH',
		'T_AS',
	);
	protected $increaseThisLine = array();
	protected $increaseNextLine = array(
		'T_CLASS_OPEN_CURLY_BRACKET',
		'T_FUNCTION_OPEN_CURLY_BRACKET',
		'T_IF_OPEN_CURLY_BRACKET',
		'T_TRY_OPEN_CURLY_BRACKET',
		'T_CATCH_OPEN_CURLY_BRACKET',
		'T_FOR_OPEN_CURLY_BRACKET',
		'T_FOREACH_OPEN_CURLY_BRACKET',
	);
	protected $decreaseThisLine = array(
		'T_CLASS_CLOSE_CURLY_BRACKET',
		'T_FUNCTION_CLOSE_CURLY_BRACKET',
		'T_IF_CLOSE_CURLY_BRACKET',
		'T_TRY_CLOSE_CURLY_BRACKET',
		'T_CATCH_CLOSE_CURLY_BRACKET',
		'T_FOR_CLOSE_CURLY_BRACKET',
		'T_FOREACH_CLOSE_CURLY_BRACKET',
	);
	protected $decreaseNextLine = array();
	
	public function tOpenTag(Token $token)
	{
		return '<?php';
	}
}