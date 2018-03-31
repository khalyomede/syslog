<?php

namespace test\Khalyomede;

use Khalyomede\Syslog;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use InvalidArgumentException;

class SyslogSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Syslog::class);
    }

    function it_should_throw_an_invalid_argument_exception_during_source_method_if_argument_is_empty() {
    	$this->shouldThrow(InvalidArgumentException::class)->during('source', ['']);
    }

    function it_should_not_allow_setting_a_port_lower_than_zero() {
    	$this->shouldThrow(InvalidArgumentException::class)->during('port', [-1]);
    }

    function it_should_not_allow_setting_a_facility_lower_than_zero() {
    	$this->shouldThrow(InvalidArgumentException::class)->during('facility', [-1]);	
    }

    function it_should_not_allow_setting_an_empty_device() {
    	$this->shouldThrow(InvalidArgumentException::class)->during('device', ['']);
    }

    function it_should_not_allow_setting_an_empty_processus() {
    	$this->shouldThrow(InvalidArgumentException::class)->during('processus', ['']);
    }

    function it_should_not_allow_setting_an_empty_identifier() {
    	$this->shouldThrow(InvalidArgumentException::class)->during('identifier', ['']);
    }
}
