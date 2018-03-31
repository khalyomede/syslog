<?php
	require( __DIR__ . '/../vendor/autoload.php' );

	use Khalyomede\Syslog;

	$log = new Syslog;

	$log->host('log.test.com')
		->port(12345)
		->facility(22)
		->source('test.test.com')
		->device('test-website')
		->processus('test-home')
		->date(new DateTime('2017-11-29 04:34:09', new DateTimeZone('Europe/Paris')));

	$log->emergency('detected forbidden access to database');
?>