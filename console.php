<?php
 /**
 * Entry point for command line interface application 
 *
 */
(PHP_SAPI == 'cli') OR die('This application work only in command line.');
class_exists('Minion_Task') OR die('Please enable the Minion module for CLI support.');

$application = 'cli';      // Application name\directory
$app_alias   = 'console/'; // Application alias, use in URL`s

require_once 'index.php';

// Load minion
set_exception_handler(array('Minion_Exception', 'handler'));
Minion_Task::factory(Minion_CLI::options())->execute();