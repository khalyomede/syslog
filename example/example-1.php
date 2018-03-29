<?php
	require( __DIR__ . '/../vendor/autoload.php' );

	use Khalyomede\Syslog;

	$log = new Syslog;

	$log->host('test.test.com')
		->port(0)
		->facility(22)
		->device('device-test')
		->processus('processus-test');

	$log->debug('test');
?>