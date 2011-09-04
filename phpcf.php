<?php
use PHP\CodeFormatter\Formatter;
use PHP\CodeFormatter\Tokenizer;
use PHP\CodeFormatter\Standards\Pear;

require 'PHP/CodeFormatter/Autoload.php';

$tokenizer = new Tokenizer();
$standard = new Pear();
$formatter = new Formatter($tokenizer, $standard);
$source = file_get_contents('tests/_testdata/Calculator.php');
$formatter->format($source);