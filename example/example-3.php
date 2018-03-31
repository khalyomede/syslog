<?php
	require( __DIR__ . '/../vendor/autoload.php' );

	use Khalyomede\Syslog;
	use Psr\Log\LogLevel;

	$log = new Syslog;

	$log->host('log.test.com')
		->port(12345)
		->facility(22)
		->source('test.test.com')
		->device('test-website')
		->processus('test-home');

	$log->log(LogLevel::ERROR, "the user could not be created because: this username already exists");
?>