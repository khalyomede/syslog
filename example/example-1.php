<?php
	require( __DIR__ . '/../vendor/autoload.php' );

	use Khalyomede\Syslog;

	$log = new Syslog;

	$log->host('log.test.com')
		->port(12345)
		->facility(22)
		->source('test.test.com')
		->device('test-website')
		->processus('test-home');

	$log->debug("user created in 5ms");
?>