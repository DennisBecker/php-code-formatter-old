<?php
namespace PHP\CodeFormatter\Standards;

class StandardsFactory
{
	public static function getInstanceFor($codingStandard)
	{
		$codingStandardClass = 'PHP\CodeFormatter\Standards\\'.$codingStandard;
		
		if (!class_exists($codingStandardClass)) {
			throw new \Exception('Unknown coding standard "'. $codingStandard .'"');
		}
		
		return new $codingStandardClass();
	}
}