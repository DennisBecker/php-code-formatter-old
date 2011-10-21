#!/usr/bin/php
<?php
require_once('PEAR/PackageFileManager2.php');
PEAR::setErrorHandling(PEAR_ERROR_DIE);

$options = array(
	'filelistgenerator' => 'file', // this copy of our source code is a CVS checkout
	'simpleoutput' => true,
	'baseinstalldir' => '/',	 // The PEAR directory install location
	'packagedirectory' => dirname(__FILE__), // We’ve put this file in the root source code dir
	'clearcontents' => true, // dump any old package.xml content (set false to append release)
	// no bundling of cvs/svn files or this generator file
	'ignore' => array('createPackage.php', 'build.xml', 'phpunit.xml.dist', 'README.markdown', '.git/', 'build/', 'tests/'),
	'dir_roles' => array(
		'tests' => 'test',
	),
);

// Oddly enough, this is a PHP source code package…

$packagexml = &PEAR_PackageFileManager2::importOptions('package.xml', $options);
$packagexml->setPackageType('php');

// Package name, summary and longer description

$packagexml->setPackage('PHP_CodeFormatter');
$packagexml->setSummary('PHP_CodeFormatter let`s you re-format your code so that it matches your coding standards');
$packagexml->setDescription('PHP_CodeFormatter is designed to replace PHP_Beautifier. PHP_Beautifier has limitations for your coding standards, especially if you want to have different formats for curly braces for methods, classes etc. which PHP_CodeFormatter addresses and gives you a solution.');

// The channel where this package is hosted. Since we’re installing from a local
// downloaded file rather than a channel we’ll pretend it’s from PEAR.

$packagexml->setChannel('dennisbecker.github.com/pear');

// Add some release notes!

$notes = "- 0.1.0 alpha version for testing";

$packagexml->setNotes($notes);

// Add file base changes

$packagexml->addGlobalReplacement('pear-config', '@PHP_BIN@', 'php_bin');
$packagexml->addGlobalReplacement('package-info', '@PACKAGE_VERSION@', 'version');
$packagexml->addRole('sh', 'script');

// Add any known dependencies such as PHP version, extensions, PEAR installer

$packagexml->setPhpDep('5.3');
$packagexml->setPearinstallerDep('1.4.0');
$packagexml->addPackageDepWithChannel('required', 'PEAR', 'pear.php.net', '1.4.0');
$packagexml->addPackageDepWithChannel('required', 'Console_CommandLine', 'pear.php.net', '1.1.3');
$packagexml->addPackageDepWithChannel('required', 'PHP_Timer', 'pear.phpunit.de', '1.0.2');
$packagexml->addPackageDepWithChannel('required', 'DirectoryScanner', 'pear.netpirates.net', '1.0.2');

// Other info, like the Lead Developers. license, version details and stability type

$packagexml->addMaintainer('lead', 'DennisBecker', 'Dennis Becker', 'mail.dennisbecker@gmail.com');
$packagexml->setLicense('New BSD License', 'http://opensource.org/licenses/bsd-license.php');
$packagexml->setAPIVersion('0.1.0');
$packagexml->setReleaseVersion('0.1.0');
$packagexml->setReleaseStability('alpha');
$packagexml->setAPIStability('alpha');

// Add this as a release, and generate XML content

$packagexml->addRelease();
$packagexml->addInstallAs('phpcf.sh', 'phpcf');

$packagexml->generateContents();

// Pass a “make” flag from the command line or browser address to actually write
// package.xml to disk, otherwise just debug it for any errors

if (isset($_GET['make']) || (isset($_SERVER['argv']) && @$_SERVER['argv'][1] == 'make')) {
	$packagexml->writePackageFile();
} else {
	$packagexml->debugPackageFile();
}
