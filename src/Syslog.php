<?php
	namespace Khalyomede;

	use Khalyomede\SyslogInterface;
	use Khalyomede\Prototype;
	use InvalidArgumentException;
	use Khalyomede\ExtensionNotFoundException;
	use RuntimeException;
	use DateTime;

	class Syslog extends Prototype implements SyslogInterface {
		protected $host;
		protected $port;
		protected $date;
		protected $source;
		protected $device;
		protected $processus;
		protected $identifier;
		protected $socket;

		/**
		 * @throws ExtensionNotFoundEception
		 */
		public function __construct() {
			$this->checkSocketExtensionIsEnabled();
			$this->resetDate();

			$this->host = null;
			$this->port = null;
			$this->source = null;
			$this->device = null;
			$this->processus = null;
			$this->identifier = null;
			$this->socket = null;
		}			

		public function __destruct() {
			if( is_resource($this->socket) === true && get_resource_type($this->socket) === 'socket' ) {
				socket_close($this->socket);
			}
		}

		public function emergency(string $message, array $context = []): Syslog {

		}

		public function alert(string $message, array $context = []): Syslog {

		}

		public function critical(string $message, array $context = []): Syslog {

		}

		public function error(string $message, array $context = []): Syslog {

		}

		public function warning(string $message, array $context = []): Syslog {

		}

		public function notice(string $message, array $context = []): Syslog {

		}

		public function info(string $message, array $context = []): Syslog {

		}

		/**
		 * @throws RuntimeException
		 * @throws InvalidArgumentException
		 */
		public function debug(string $message, array $context = []): Syslog {
			$this->checkValueNonEmpty($message, 'debug', 1);
			
			$this->bootstrap('debug');

			$contextualized_message = $this->getContextualizedMessage($message, $context, 'debug', 2);

			$this->sendLog($contextualized_message, 'debug');

			return $this;
		}

		public function log(string $level, string $message, array $context = []): Syslog {

		}

		public function source(string $source): Syslog {
			$this->checkValidUrl($source, 'source', 1);

			$this->source = $source;

			return $this;
		}

		/**
		 * @throws ExtensionNotFoundException
		 */
		private function checkSocketExtensionIsEnabled() {
			if( extension_loaded('sockets') === false ) {
				throw new ExtensionNotFoundException('Syslog:: expects extension "sockets" to be loaded');
			}
		}

		/**
		 * @throws InvalidArgumentException
		 */
		public function host(string $host): Syslog {
			$this->checkValueNonEmpty($host, 'host', 1);
			$this->checkValidUrl($host, 'host', 1);

			$this->host = $host;

			return $this;
		}

		/**
 		 * @throws InvalidArgumentException
		 */
		public function port(int $port): Syslog {
			$this->checkValueGreaterOrEqualToZero($port, 'port', 1);

			$this->port = $port;

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 */
		public function facility(int $facility): Syslog {
			$this->checkValueGreaterOrEqualToZero($facility, 'facility', 1);

			$this->facility = $facility;

			return $this;
		}

		public function date(DateTime $date): Syslog {
			$this->date = $date;

			return $this;
		}

		public function device(string $device): Syslog {
			$this->checkValueNonEmpty($device, 'device', 1);

			$this->device = $device;

			return $this;
		}

		public function processus(string $processus): Syslog {
			$this->checkValueNonEmpty($processus, 'processus', 1);

			$this->processus = $processus;

			return $this;
		}

		public function identifier(string $identifier): Syslog {
			$this->checkValueNonEmpty($identifier);

			$this->identifier = $identifier;

			return $this;
		}

		private function resetDate() {
			$this->date = null;
		}

		/**
		 * @throws InvalidArgumentException
		 */
		private function checkValidUrl(string $url, string $caller, int $parameter_index) {
			$normalized_url = trim($url);

			if( substr($normalized_url, 0, 7) !== 'http://' && substr($normalized_url, 0, 8) !== 'https://' ) {
				$normalized_url = 'http://' . $normalized_url;
			}

			if( filter_var($normalized_url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) === false ) {
				throw new InvalidArgumentException(sprintf('Syslog::%s expects parameter %s to be a valid url, value "%s" given', 
					$caller,
					$parameter_index,
					$url
				));
			}
		}

		/**
		 * @throws InvalidArgumenException
		 */
		private function checkValueGreaterOrEqualToZero(int $value, string $caller, int $parameter_index) {
			if( $value < 0 ) {
				throw new InvalidArgumentException(sprintf('Syslog::%s expects parameter %s to be greater or equal to zero (value "%s" given)', 
					$value,
					$caller,
					$parameter_index
				));
			}
		}

		/**
		 * @throws InvalidArgumentException
		 */
		private function checkValueNonEmpty(string $value, string $caller, int $parameter_index) {
			if( isset(trim($value)[0]) === false ) {
				throw new InvalidArgumentException(sprintf('Syslog::%s expects parameter %s to be a non empty string',
					$caller,
					$parameter_index
				));
			}
		}

		/**
		 * @throws RuntimException
		 */
		private function createSocketIfNotCreated(string $caller) {
			if( is_null($this->socket) || get_resource_type($this->socket) !== 'socket' ) {
				$this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

				if( $this->socket === false ) {
					throw new RuntimeException(sprintf('Syslog::%s encoutered an error during the creation of the socket (detail: %s)', 
						$caller,
						socket_strerror(socket_last_error())
					));
				}
			}
		}

		/**
   		 * @throws RuntimeException
		 */
		private function sendLog(string $message, string $severity) {
			$syslog = $this->getSyslog($message, $severity);

			$success = socket_sendto($this->socket, $syslog, strlen($syslog), MSG_DONTROUTE, $this->host, $this->port);

			if( $success === false ) {
				throw new RuntimeException(sprintf('Syslog::%s ecountered an error during the binding of the host to the socket (detail: %s)', 
					$caller,
					socket_strerror(socket_last_error())
				));
			}
		}

		/**
		 * @throws LogicException
		 */
		private function checkValueSet(string $property_name, string $caller) {
			if( $this->{$property_name} === null ) {
				throw new LogicException(sprintf('Syslog::%s expects property %s to be set', 
					$caller,
					$property_name
				));
			}
		}

		/**
		 * @throws LogicException
		 */
		private function checkFieldsAllFilled(string $caller) {
			$this->checkValueSet('host', $caller);
			$this->checkValueSet('port', $caller);
			$this->checkValueSet('source', $caller);
			$this->checkValueSet('device', $caller);
			$this->checkValueSet('processus', $caller);
		}

		/**
		 * @throws InvalidArgumentException
		 */
		private function getContextualizedMessage(string $message, array $context = [], string $caller, int $parameter_index): string {
			$this->checkContextIsValid($context, $caller, $parameter_index);

			$contextualized_message = $message;

			foreach( $context as $key => $value ) {
				$contextualized_message = str_replace("{$key}", $value, $contextualized_message);
			}

			return $contextualized_message;
		}

		/**
		 * @throws InvalidArgumentException
		 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md#12-message
		 */
		private function checkContextIsValid(array $context = [], string $caller, int $parameter_index) {
			$index = 0;

			foreach( $context as $key => $value ) {
				if( is_int($key) === true ) {
					throw new InvalidArgumentException(sprintf('Syslog::%s expects parameter %s to be an array of key-pairs, but it seems one of the item of your array has only a value at index %s', 
						$caller,
						$parameter_index,
						$index
					));
				}

				if( preg_match('/^[a-z0-9_.]+$/i', $key) === false ) {
					throw new InvalidArgumentException(sprintf('Syslog::%s expects parameter %s to have a valid key (it must contains only letters, numbers, underscores and periods), but value "%s" did not matched the requirements at index %s', 
						$caller,
						$parameter_index,
						$key,
						$index
					));
				}

				$index++;
			}
		}

		private function autoFillEmptyNonMandatoryFields() {
			$this->date = $this->date ?? new DateTime;
			$this->identifier = $this->identifier ?? '-';
		}

		private function getSyslog(string $message, string $severity) {
			$escaped_message = str_replace('%', '%%', $message);
			$severity = $this->severityToInt($severity);

			return sprintf('<%s>1 %s %s %s %s - %s %s', 
				($this->facility * 8) + $severity,
				$this->date->format(DateTime::RFC3339_EXTENDED),
				$this->source,
				$this->device,
				$this->processus,
				$this->identifier,
				$escaped_message
			);
		}

		/**
		 * @see https://tools.ietf.org/html/rfc5424#section-6.2.1
		 */
		private function severityToInt(string $severity) {
			switch($severity) {
				case 'emergency': return 0;
				case 'alert': return 1;
				case 'critical': return 2;
				case 'error': return 3;
				case 'warning': return 4;
				case 'notice': return 5;
				case 'informational': return 6;
				case 'debug': return 7;
			}
		}

		private function bootstrap(string $caller) {
			$this->checkFieldsAllFilled($caller);		
			$this->createSocketIfNotCreated($caller);			
			$this->autoFillEmptyNonMandatoryFields();
		}
	}
?>