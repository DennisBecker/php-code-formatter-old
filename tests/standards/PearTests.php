<?php
require '../../src/PHP/CodeFormatter/Autoload.php';

$_SERVER['argv'] = array(
	'PearTests.php',
	'--standard=PEAR',
	'--output=result',
	'_testdata',
);
$_SERVER['argc'] = 4;


use PHP\CodeFormatter\Command;

Command::main();