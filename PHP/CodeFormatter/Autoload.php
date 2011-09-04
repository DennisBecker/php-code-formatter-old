<?php // this is an autogenerated file - do not edit (created Sun, 04 Sep 2011 23:43:44 +0200)
spl_autoload_register(
   function($class) {
      static $classes = null;
      if ($classes === null) {
         $classes = array(
            'php\\codeformatter\\formatter' => '/Formatter.php',
            'php\\codeformatter\\standards\\abstractstandard' => '/Standards/AbstractStandard.php',
            'php\\codeformatter\\standards\\pear' => '/Standards/Pear.php',
            'php\\codeformatter\\token' => '/Token.php',
            'php\\codeformatter\\tokenizer' => '/Tokenizer.php'
          );
      }
      $cn = strtolower($class);
      if (isset($classes[$cn])) {
         require __DIR__ . $classes[$cn];
      }
   }
);
