<?php

class Calculator
{
	public function add($a, $b)
	{
		if(!is_int($a) || !is_int($b))
		{
			throw new RuntimeException('bad input parameters - integer needed');
		}
		
		return $a+$b;
	}
}