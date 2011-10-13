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
		'T_SWITCH_OPEN_CURLY_BRACKET',
		'T_SWITCH_CLOSE_CURLY_BRACKET',
		'T_CASE_OPEN_CURLY_BRACKET',
		'T_CASE_CLOSE_CURLY_BRACKET',
		'T_ARRAY_OPEN_ROUND_BRACKET',
		'T_COLON',
		'T_SEMICOLON',
		'T_SEMICOLON_FOR_ARRAY',
		'T_COMMA_FOR_ARRAY',
		'T_DOC_COMMENT',
	);
	protected $emptyLineBefore = array(
		'T_DOC_COMMENT',
		'T_RETURN',
		'T_IF',
		'T_FOR',
		'T_FOREACH',
		'T_WHILE',
		'T_SWITCH',
		'T_TRY',
	);
	protected $emptyLineAfter = array(
		'T_FUNCTION_CLOSE_CURLY_BRACKET',
		'T_SEMICOLON_FOR_ARRAY',
	);
	protected $spaceBefore = array(
		'T_CLASS_OPEN_CURLY_BRACKET',
		'T_FUNCTION_OPEN_CURLY_BRACKET',
		'T_IF_OPEN_CURLY_BRACKET',
		'T_FOR_OPEN_CURLY_BRACKET',
		'T_FOREACH_OPEN_CURLY_BRACKET',
		'T_SWITCH_OPEN_CURLY_BRACKET',
		'T_PLUS',
		'T_MINUS',
		'T_EQUAL',
		'T_DOT',
		'T_CATCH',
		'T_AS',
		'T_EXTENDS',
		'T_IMPLEMENTS',
		'T_IS_EQUAL',
		'T_IS_GREATER_OR_EQUAL',
		'T_IS_IDENTICAL',
		'T_IS_NOT_EQUAL',
		'T_IS_NOT_IDENTICAL',
		'T_IS_SMALLER_OR_EQUAL',
		'T_AND_EQUAL',
		'T_MINUS_EQUAL',
		'T_MOD_EQUAL',
		'T_MUL_EQUAL',
		'T_DIV_EQUAL',
		'T_XOR_EQUAL',
		'T_CONCAT_EQUAL',
		'T_LOGICAL_AND',
		'T_LOGICAL_OR',
		'T_LOGICAL_XOR',
		'T_BOOLEAN_AND',
		'T_BOOLEAN_OR',
		'T_DOUBLE_ARROW',
	);
	protected $spaceAfter = array(
		'T_CLASS',
		'T_IMPLEMENTS',
		'T_EXTENDS',
		'T_FINAL',
		'T_ABSTRACT',
		'T_PUBLIC',
		'T_PROTECTED',
		'T_PRIVATE',
		'T_FUNCTION',
		'T_COMMA',
		'T_IF',
		'T_FOR',
		'T_FOREACH',
		'T_SWITCH',
		'T_CASE',
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
		'T_CLONE',
		'T_ECHO',
		'T_IS_EQUAL',
		'T_IS_GREATER_OR_EQUAL',
		'T_IS_IDENTICAL',
		'T_IS_NOT_EQUAL',
		'T_IS_NOT_IDENTICAL',
		'T_IS_SMALLER_OR_EQUAL',
		'T_AND_EQUAL',
		'T_MINUS_EQUAL',
		'T_MOD_EQUAL',
		'T_MUL_EQUAL',
		'T_DIV_EQUAL',
		'T_XOR_EQUAL',
		'T_CONCAT_EQUAL',
		'T_LOGICAL_AND',
		'T_LOGICAL_OR',
		'T_LOGICAL_XOR',
		'T_BOOLEAN_AND',
		'T_BOOLEAN_OR',
		'T_DOUBLE_ARROW',
		'T_SEMICOLON_IN_FOR',
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
		'T_ARRAY_OPEN_ROUND_BRACKET',
	);
	protected $decreaseThisLine = array(
		'T_CLASS_CLOSE_CURLY_BRACKET',
		'T_FUNCTION_CLOSE_CURLY_BRACKET',
		'T_IF_CLOSE_CURLY_BRACKET',
		'T_TRY_CLOSE_CURLY_BRACKET',
		'T_CATCH_CLOSE_CURLY_BRACKET',
		'T_FOR_CLOSE_CURLY_BRACKET',
		'T_FOREACH_CLOSE_CURLY_BRACKET',
		'T_ARRAY_CLOSE_ROUND_BRACKET',
	);
	protected $decreaseNextLine = array();
	
	public function tOpenTag(Token $token)
	{
		return '<?php';
	}
}