# PHP_CodeFormatter
PHP_CodeFormatter has been started due to the fact that PHP_Beautifier is
extremly ugly to extend for your own coding standard. Other solutions like
PHP_CodeBeautifier are even more worse. That's why I intend to recreate a nice
code formatting tool which will make use of more modern PHP OOP.

## Planned Releases
- 0.1 alpha will have support for PEAR Coding Standard
  - this release will be a proof-of-concept for experimental usage
  - feedback highly appreciated
- 0.2 alpha refactoring code to OOP and add unit tests
  - with this release I want to aim the goal of using modern PHP 5.3 OOP
- 0.5 beta will be a refactoring after analyzes with xhprof


### Note
- Version 1.0 will be released after beta phase
- PHP 5.4 support with traits will probably be released as an additional package

## Release Blockers for 0.1 alpha
- integrate nested array support
- add Pirium PEAR channel on github
- create PEAR package for PHP_CodeFormatter