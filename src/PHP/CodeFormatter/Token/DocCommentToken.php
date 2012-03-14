<?php

namespace PHP\CodeFormatter\Token;

use PHP\CodeFormatter\Token;

/**
 * 
 * @author Dennis Becker
 * @todo use as a trait when PHP 5.4 is ready
 */
class DocCommentToken extends Token
{
	/**
	 * PHP DocComment block of token
	 *
	 * @var string
	 */
	protected $docComment;
	
	public function setDocComment($docComment)
	{
		$this->docComment = $docComment;
	}
	
	public function getDocComment()
	{
		return $this->docComment;
	}
}