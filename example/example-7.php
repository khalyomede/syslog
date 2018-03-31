<?php
	require( __DIR__ . '/../vendor/autoload.php' );

	use Khalyomede\Syslog;

	$log = new Syslog;

	$log->prototype('oneHourAgo', function() {
		$this->date->sub(new DateInterval('PT1H'));

		return $this;
	});

	$log->host('log.test.com')
		->port(12345)
		->facility(22)
		->source('test.test.com')
		->device('test-website')
		->processus('test-home')
		->date(new DateTime)
		->oneHourAgo();

	$log->info('test');
?>