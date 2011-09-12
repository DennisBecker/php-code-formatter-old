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

use PHP\CodeFormatter\Formatter;
use PHP\CodeFormatter\Tokenizer;
use PHP\CodeFormatter\Standards\StandardsFactory;
use \TheSeer\Tools\DirectoryScanner;

require 'Console/CommandLine.php';
require 'PHP/Timer.php';
require 'TheSeer/DirectoryScanner/autoload.php';

/**
 * Command line utility class
 *
 * @package    PHP_CodeFormatter
 * @author     Dennis Becker
 * @copyright  2011 Dennis Becker
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link       https://github.com/DennisBecker/php-code-formatter
 */
class Command
{
	/**
	 * @var Console_CommandLine
	 */
	protected static $parser;
	
	/**
	 * Initializes Console_CommandLine and handles all arguments
	 */
	public static function main()
	{
		self::$parser = new \Console_CommandLine();
		self::$parser->description = 'PHP_CodeFormatter 0.1 by Dennis Becker';
		self::$parser->version = '0.1';
		self::$parser->addOption('standard', array(
		    'long_name'   => '--standard',
		    'description' => 'Coding Standard like PEAR',
		    'action'      => 'StoreString'
		));
		self::$parser->addOption('output', array(
		    'long_name'   => '--output',
		    'description' => 'Output directory of modified files',
		    'action'      => 'StoreString'
		));
		self::$parser->addArgument(
			'directory'
		);
		try {
		    $result = self::$parser->parse();
		    
		    self::run($result->options['standard'], $result->args['directory'], $result->options['output']);
		} catch (\Exception $exc) {
		    self::$parser->displayError($exc->getMessage());
		}
	}
	
	/**
	 * Executes the PHP_CodeFormatter with the given paremeters
	 * 
	 * @param string $codingStandard
	 * @param string $directory
	 * @param string $outputDirectory
	 */
	private function run($codingStandard, $directory, $outputDirectory = null)
	{
		$tokenizer = new Tokenizer();
		$standard = StandardsFactory::getInstanceFor($codingStandard);
		$formatter = new Formatter($tokenizer, $standard);
		$scanner = new DirectoryScanner();
		$scanner->addInclude('*.php');
		
		if ($outputDirectory !== null) {
			$dir = explode('/', $outputDirectory);
			$outputDirectory = implode('/', $dir) . '/';
			
			self::createDirectory($outputDirectory);
		}
		
		foreach ($scanner($directory) as $file) {
			$source = file_get_contents($file->getPathname());
			$formattedSourceCode = $formatter->format($source);
			
			$path = explode('/', $file->getPath());
			$folderStructureCount = count($path);
			if ($folderStructureCount > 0) {
				$path[0] = $outputDirectory.$path[0];
				self::createDirectory($path[0]);
			}
			
			for ($i = 1; $i < $folderStructureCount; $i++) {
				$path[$i] = $path[$i-1].'/'.$path[$i];
				self::createDirectory($path[$i]);
			}
			
			file_put_contents($outputDirectory.$file->getPathname(), $formattedSourceCode);
		}

		self::$parser->outputter->stdout(\PHP_Timer::resourceUsage()."\n");
	}
	
	protected static function createDirectory($path)
	{
		if(!is_dir($path)) {
			mkdir($path);
		}
	}
}