<?php
define('LOG4PHP_DIR', dirname(__FILE__).'/../log4php');
define('LOG4PHP_CONFIGURATION', 'mail.properties');

require_once LOG4PHP_DIR.'/LoggerManager.php';
$logger = LoggerManager::getRootLogger();
$logger->fatal("Some critical message!");
?>