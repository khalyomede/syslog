<?php
	require( __DIR__ . '/../vendor/autoload.php' );

	use Khalyomede\Syslog;

	$log = new Syslog;

	$log->host('test.test.com')
		->port(0)
		->facility(22)
		->source('test')
		->device('test')
		->processus('test');

	$log->debug('test');
?>