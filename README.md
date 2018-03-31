# Syslog

Log into your Syslog destination.

```php
$log->host('some.vendor.com')
  ->port(92883)
  ->facility(22)
  ->source('my.company.io')
  ->device('website')
  ->processus('price-index');

$log->debug('page loaded in 3.840 s');
```

## Summary

- [Prerequistes](#prerequistes)
- [Installation](#installation)
- [Examples of uses](#examples-of-uses)
- [Methods definitions](#methods-definitions)
- [Prototype ready](#prototype-ready)

## Prerequistes

- PHP version >= 7.0.0
- Socket extension enabled (`php_sockets.dll` on windows or `php_sockets.so` on Linux distributions)

## Installation

In your project folder:

```bash
composer require khalyomede/syslog:1.*
```

## Examples of uses

All the examples can be found in the `/example` folder.

- [Example 1: logging into your log destination](#example-1-logging-into-your-log-destination)
- [Example 2: templatize your message for logging](#example-2-templatize-your-message-for-logging)
- [Example 3: use a generic method for logging](#example-3-use-a-generic-method-for-logging)
- [Example 4: templatize when using the generic logging](#example-4-templatize-when-using-the-generic-logging)
- [Example 5: force the date before logging](#example-5-force-the-date-before-loggin)
- [Example 6: specify an indentifier for your next logs](#example-6-specify-an-identifier-for-your-next-logs)

### Example 1: logging into your log destination

```php
use Khalyomede\Syslog;

$log = new Syslog;

$log->host('log.test.com')
  ->port(12345)
  ->facility(22)
  ->source('test.test.com')
  ->device('test-website')
  ->processus('test-home');

$log->debug("user created in 5ms");
```

### Example 2: templatize your message for logging

```php
use Khalyomede\Syslog;

$log = new Syslog;

$log->host('log.test.com')
  ->port(12345)
  ->facility(22)
  ->source('test.test.com')
  ->device('test-website')
  ->processus('test-home');

$message = "user {username} created successfuly";

$log->info($message, ['username' => 'johndoe']);
```

### Example 3: use a generic method for logging

```php
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
```

If you do not want to pass through the class constants of `LogLevel`, you can provide a string instead:

```php
$log->log('error' 'the user could not be created because: this username already exists');
```

Beware that the informational severity string equivalent is `info`.

### Example 4: templatize when using the generic logging

```php
use Khalyomede\Syslog;
use Psr\Log\LogLevel;

$log = new Syslog;

$log->host('log.test.com')
  ->port(12345)
  ->facility(22)
  ->source('test.test.com')
  ->device('test-website')
  ->processus('test-home');

$message = "user {username} created successfuly";

$log->log(LogLevel::ERROR, $message, ['username' => 'johndoe']);
```

### Example 5: force the date before logging

```php
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
```

### Example 6: specify an indentifier for your next logs

```php
use Khalyomede\Syslog;

$log = new Syslog;

$log->host('log.test.com')
  ->port(12345)
  ->facility(22)
  ->source('test.test.com')
  ->device('test-website')
  ->processus('test-home')
  ->identifier('AZXT6');

$log->debug("database optimized in 33.09 s.");
```

The identifier will stick to your next logs. If you would like to clear it at a point, you can use:

```php
$log->deleteIdentifier();
```

It returns an instance of `Khalyomede\Syslog` so you can chain it with any other method.

## Methods definitions

- [`alert`](#alert)
- [`critical`](#critical)
- [`date`](#date)
- [`debug`](#debug)
- [`deleteIdentifier`](#deleteidentifier)
- [`device`](#device)
- [`emergency`](#emergency)
- [`error`](#error)
- [`facility`](#facility)
- [`host`](#host)
- [`identifier`](#identifier)
- [`info`](#info)
- [`log`](#log)
- [`notice`](#notice)
- [`port`](#port)
- [`processus`](#processus)
- [`source`](#source)
- [`warning`](#warning)

### alert

Sends a message to the log destination with an alert severity.

```php
public function alert(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### critical

Sends a message to the log destination with a critical severity.

```php
public function critical(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### date

```php
public function date(DateTime $date): Syslog
```

### debug

Sends a message to the log destination with a debug severity.

```php
public function debug(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### deleteIdentifier

Reset the identifier to its empty value.

```php
public function deleteIdentifier(): Syslog
```

### device

Set the name of the device that is sending the log. For more information, see the definition of this attribute on the [Syslog RFC5424 documentation](https://tools.ietf.org/html/rfc5424#section-6.2.5).

```php
public function device(string $device): Syslog
```

### emergency

Sends a message to the log destination with an emergency severity.

```php
public function emergency(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### error

Sends a message to the log destination with an error severity.

```php
public function error(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### facility

Set the target plateform. For more information, see the definition of this attribute on the [Syslog RFC5424 documentation](https://tools.ietf.org/html/rfc5424#section-6.2.1).

```php
public function facility(int $facility): Syslog
```

### host

Set the target log destination host.

```php
public function host(string $host): Syslog
```

**Note**

The value should be an IP or a valid domain.

### identifier

Set an optional identifier to group your logs.

```php
public function source(string $source): Syslog
```

### info

Sends a message to the log destination with an info severity.

```php
public function info(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### log

Log using an opt-in severity parameter. This has the same effect than any other others severity logging methods.

```php
public function log(string $level, string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the severity is empty
- If the severity is not one of the following: emergency, alert, critical, error, warning, notice, info, debug
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### notice

Sends a message to the log destination with a notice severity.

```php
public function notice(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

### port

Set the port of the log destination server address.

```php
public function port(int $port): Syslog
```

### processus

Set the original processus that is responsible for this log. For more information, see the definition of this attribute on the [Syslog RFC5424 documentation](https://tools.ietf.org/html/rfc5424#section-6.2.6).

```php
public function processus(string $processus): Syslog
```

### source

Set the original server that generated this log. For more information, see the definition of this attribute on the [Syslog RFC5424 documentation](https://tools.ietf.org/html/rfc5424#section-6.2.4).

```php
public function source(string $source): Syslog
```

### warning

Sends a message to the log destination with a warning severity.

```php
public function warning(string $message, array $context = []): Syslog
```

**Note**
The keys of the context should respect the following format: lower case, numbers, with underscores and periods only.

**Exception**

`InvalidArgumentException`:
- If the message is empty
- If the context does not contains an array of key-pairs values
- If one of the context keys is not properly formated

`LogicException`:
- If one of the following properties are not filled: host, post, source, device, processus

`RuntimeException`:
- If the socket creation failed
- If the message could not be sent through the socket connection

## Prototype ready

This class lets you extend its functionality to your needs without having to dive into the source code. For example:

```php
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
```

For more information, check [khalyomede/prototype](https://github.com/khalyomede/prototype) documentation.