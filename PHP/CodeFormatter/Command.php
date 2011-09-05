<?php
namespace PHP\CodeFormatter;

use PHP\CodeFormatter\Formatter;
use PHP\CodeFormatter\Tokenizer;
use PHP\CodeFormatter\Standards\StandardsFactory;

require 'Console/CommandLine.php';
require 'PHP/Timer.php';

class Command
{
	protected static $parser;
	
	public static function main()
	{
		static::$parser = new \Console_CommandLine();
		static::$parser->description = 'PHP_CodeFormatter 0.1 by Dennis Becker';
		static::$parser->version = '0.1';
		static::$parser->addOption('standard', array(
		    'long_name'   => '--standard',
		    'description' => 'Coding Standard like PEAR',
		    'action'      => 'StoreString'
		));
		static::$parser->addOption('output', array(
		    'long_name'   => '--output',
		    'description' => 'Output directory of modified files',
		    'action'      => 'StoreString'
		));
		static::$parser->addArgument(
			'directory'
		);
		try {
		    $result = static::$parser->parse();
		    
//		    var_dump($result->options);
//		    var_dump($result->args);
		    
		    static::run($result->options['standard'], $result->args['directory'], $result->options['output']);
		} catch (\Exception $exc) {
		    static::$parser->displayError($exc->getMessage());
		}
	}
	
	private function run($codingStandard, $directory, $outputDirectory = null)
	{
		$tokenizer = new Tokenizer();
		$standard = StandardsFactory::getInstanceFor($codingStandard);
		$formatter = new Formatter($tokenizer, $standard);
		$source = file_get_contents('tests/_testdata/Calculator.php');
		$formatter->format($source);
		static::$parser->outputter->stdout(\PHP_Timer::resourceUsage()."\n");
	}
}