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
	 * @var \PHP\CodeFormatter\Formatter
	 */
	protected static $formatter;
	
	/**
	 * @var Console_CommandLine
	 */
	protected static $parser;
	
	protected static $outputDirectory;
	
	/**
	 * Initializes Console_CommandLine and handles all arguments
	 */
	public static function main()
	{
		self::$parser = new \Console_CommandLine();
		self::$parser->description = 'PHP_CodeFormatter 0.1 by Dennis Becker';
		self::$parser->version = '@PACKAGE_VERSION@';
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
		$recursive = true;
		$tokenizer = new Tokenizer();
		$standard = StandardsFactory::getInstanceFor($codingStandard);
		self::$formatter = new Formatter($tokenizer, $standard);
		
		if ($outputDirectory !== null) {
			if($outputDirectory !== null && substr($outputDirectory, -1, 1) != '/') {
				$outputDirectory .= '/';
			}
			
			self::$outputDirectory = $outputDirectory;
			self::createDirectory($outputDirectory);
		}
		
		if(is_file($directory)) {
			$file = new \SplFileInfo($directory);
			self::parseFile($file);
		} else {
			$scanner = new DirectoryScanner();
			$scanner->addInclude('*.php');
			
			foreach ($scanner($directory, $recursive) as $file) {
				self::parseFile($file);
			}
		}

		self::$parser->outputter->stdout(\PHP_Timer::resourceUsage()."\n");
	}
	
	protected static function parseFile($file)
	{
		echo $file->getPathname()."\n";
		
		$source = file_get_contents($file->getPathname());
		$formattedSourceCode = self::$formatter->format($source);
		
		$path = explode('/', $file->getPath());
		$folderStructureCount = count($path);
		
		if ($folderStructureCount > 1 && $path[0] != '') {
			$path[0] = self::$outputDirectory.$path[0];
			self::createDirectory($path[0]);
		}
		
		for ($i = 1; $i < $folderStructureCount; $i++) {
			$path[$i] = $path[$i-1].'/'.$path[$i];
			self::createDirectory($path[$i]);
		}
		
		file_put_contents(self::$outputDirectory.$file->getPathname(), $formattedSourceCode);
	}
	
	protected static function createDirectory($path)
	{
		if(!is_dir($path)) {
			mkdir($path);
		}
	}
}
