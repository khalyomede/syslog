<?php
	namespace Khalyomede;

	use Khalyomede\SyslogInterface;
	use Khalyomede\Prototype;
	use Psr\Log\LogLevel;
	use DateTime;

	use Khalyomede\ExtensionNotFoundException;
	use InvalidArgumentException;
	use RuntimeException;
	use LogicException;

	class Syslog extends Prototype implements SyslogInterface {
		/**
		 * @var string
		 * @since v1.0.0
		 */
		protected $host;

		/**
		 * @var int
		 * @since v1.0.0
		 */
		protected $port;
		
		/**
		 * @var DateTime
		 * @since v1.0.0
		 */
		protected $date;

		/**
 		 * @var DateTime
		 */
		protected $requested_at;
		
		/**
		 * @var string
		 * @since v1.0.0
		 */
		protected $source;
		
		/**
		 * @var string
		 * @since v1.0.0
		 */
		protected $device;
		
		/**
		 * @var string
		 * @since v1.0.0
		 */
		protected $processus;
		
		/**
		 * @var string
		 * @since v1.0.0
		 */
		protected $identifier;
		
		/**
		 * @var resource
		 * @since v1.0.0
		 */
		protected $socket;

		/**
		 * @throws ExtensionNotFoundEception
		 * @since v1.0.0
		 */
		public function __construct() {
			$this->checkSocketExtensionIsEnabled();
			$this->resetDates();

			$this->host = null;
			$this->port = null;
			$this->source = null;
			$this->device = null;
			$this->processus = null;
			$this->identifier = null;
			$this->socket = null;
		}			

		/**
		 * @since v1.0.0
		 */
		public function __destruct() {
			if( is_resource($this->socket) === true && get_resource_type($this->socket) === 'socket' ) {
				socket_close($this->socket);
			}
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function emergency(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::EMERGENCY, 1);			
			$this->bootstrap(LogLevel::EMERGENCY);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::EMERGENCY, 2);

			$this->sendLog($contextualized_message, LogLevel::EMERGENCY, LogLevel::EMERGENCY);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function alert(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::ALERT, 1);			
			$this->bootstrap(LogLevel::ALERT);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::ALERT, 2);

			$this->sendLog($contextualized_message, LogLevel::ALERT, LogLevel::ALERT);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function critical(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::CRITICAL, 1);			
			$this->bootstrap(LogLevel::CRITICAL);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::CRITICAL, 2);

			$this->sendLog($contextualized_message, LogLevel::CRITICAL, LogLevel::CRITICAL);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
 		 * @since v1.0.0
		 */
		public function error(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::ERROR, 1);			
			$this->bootstrap(LogLevel::ERROR);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::ERROR, 2);

			$this->sendLog($contextualized_message, LogLevel::ERROR, LogLevel::ERROR);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function warning(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::WARNING, 1);			
			$this->bootstrap(LogLevel::WARNING);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::WARNING, 2);

			$this->sendLog($contextualized_message, LogLevel::WARNING, LogLevel::WARNING);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function notice(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::NOTICE, 1);			
			$this->bootstrap(LogLevel::NOTICE);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::NOTICE, 2);

			$this->sendLog($contextualized_message, LogLevel::NOTICE, LogLevel::NOTICE);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function info(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::INFO, 1);			
			$this->bootstrap(LogLevel::INFO);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::INFO, 2);

			$this->sendLog($contextualized_message, LogLevel::INFO, LogLevel::INFO);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function debug(string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($message, LogLevel::DEBUG, 1);			
			$this->bootstrap(LogLevel::DEBUG);

			$contextualized_message = $this->getContextualizedMessage($message, $context, LogLevel::DEBUG, 2);

			$this->sendLog($contextualized_message, LogLevel::DEBUG, LogLevel::DEBUG);
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		public function log(string $level, string $message, array $context = []): Syslog {
			$this->timestamp();
			$this->checkValueNonEmpty($level, 'log', 1);

			$formated_level = $this->getFormatedLevel($level, 'log', 1);

			$this->bootstrap('log');

			$contextualized_message = $this->getContextualizedMessage($message, $context, 'log', 2);

			$this->sendLog($contextualized_message, $level, 'log');
			$this->resetDates();

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		public function source(string $source): Syslog {
			$this->checkValidUrl($source, 'source', 1);

			$this->source = $source;

			return $this;
		}

		/**
		 * @throws ExtensionNotFoundException
		 * @since v1.0.0
		 */
		private function checkSocketExtensionIsEnabled() {
			if( extension_loaded('sockets') === false ) {
				throw new ExtensionNotFoundException('Syslog:: expects extension "sockets" to be loaded');
			}
		}

		/**
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		public function host(string $host): Syslog {
			$this->checkValueNonEmpty($host, 'host', 1);
			$this->checkValidUrl($host, 'host', 1);

			$this->host = $host;

			return $this;
		}

		/**
 		 * @throws InvalidArgumentException
 		 * @since v1.0.0
		 */
		public function port(int $port): Syslog {
			$this->checkValueGreaterOrEqualToZero($port, 'port', 1);

			$this->port = $port;

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		public function facility(int $facility): Syslog {
			$this->checkValueGreaterOrEqualToZero($facility, 'facility', 1);

			$this->facility = $facility;

			return $this;
		}

		/**
		 * @since v1.0.0
		 */
		public function date(DateTime $date): Syslog {
			$this->date = $date;

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		public function device(string $device): Syslog {
			$this->checkValueNonEmpty($device, 'device', 1);

			$this->device = $device;

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		public function processus(string $processus): Syslog {
			$this->checkValueNonEmpty($processus, 'processus', 1);

			$this->processus = $processus;

			return $this;
		}

		/**
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		public function identifier(string $identifier): Syslog {
			$this->checkValueNonEmpty($identifier, 'identifier', 1);

			$this->identifier = $identifier;

			return $this;
		}

		/**
		 * @since v1.0.0
		 */
		public function deleteIdentifier(): Syslog {
			$this->identifier = null;

			return $this;
		}

		/**
		 * @return void
		 * @since v1.0.0
		 */
		private function resetDates() {
			$this->date = null;
			$this->requested_at = null;
		}

		/**
		 * @return void
		 * @throws InvalidArgumentException
		 * @todo This validation is not reliable, we need to find a reliable way to validate domains/urls
		 * @since v1.0.0
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
		 * @return void
		 * @throws InvalidArgumenException
		 * @since v1.0.0
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
		 * @return void
		 * @throws InvalidArgumentException
		 * @since v1.0.0
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
		 * @return void
		 * @throws RuntimException
		 * @since v1.0.0
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
		 * @return void
   		 * @throws RuntimeException
   		 * @since v1.0.0
		 */
		private function sendLog(string $message, string $severity, string $caller) {
			$syslog = $this->getSyslog($message, $severity);

			$success = @socket_sendto($this->socket, $syslog, strlen($syslog), MSG_DONTROUTE, $this->host, $this->port);

			if( $success === false ) {
				throw new RuntimeException(sprintf('Syslog::%s ecountered an error during the sending of the of the message (detail: %s)', 
					$caller,
					socket_strerror(socket_last_error())
				));
			}
		}

		/**
		 * @return void
		 * @throws LogicException
		 * @since v1.0.0
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
		 * @return void
		 * @throws LogicException
		 * @since v1.0.0
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
		 * @since v1.0.0
		 */
		private function getContextualizedMessage(string $message, array $context = [], string $caller, int $parameter_index): string {
			$this->checkContextIsValid($context, $caller, $parameter_index);

			$contextualized_message = $message;

			foreach( $context as $key => $value ) {
				$contextualized_message = str_replace("{" . $key . "}", $value, $contextualized_message);
			}

			return $contextualized_message;
		}

		/**
		 * @return void
		 * @throws InvalidArgumentException
		 * @since v1.0.0
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

		/**
		 * @return void
		 * @since v1.0.0
		 */
		private function autoFillEmptyNonMandatoryFields() {
			$this->date = $this->date ?? $this->requested_at;
			$this->identifier = $this->identifier ?? '-';
		}

		/**
		 * @return void
		 * @since v1.0.0
		 */
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
		 * @since v1.0.0
		 * @see https://tools.ietf.org/html/rfc5424#section-6.2.1
		 */
		private function severityToInt(string $severity): int {
			switch($severity) {
				case LogLevel::EMERGENCY: return 0;
			    case LogLevel::ALERT: return 1;
			    case LogLevel::CRITICAL: return 2;
			    case LogLevel::ERROR: return 3;
			    case LogLevel::WARNING: return 4;
			    case LogLevel::NOTICE: return 5;
			    case LogLevel::INFO: return 6;
			    case LogLevel::DEBUG: return 7;
			}
		}

		/**
		 * @return void
		 * @throws LogicException
		 * @throws RuntimeException
		 * @since v1.0.0
		 */
		private function bootstrap(string $caller) {
			$this->checkFieldsAllFilled($caller);		
			$this->createSocketIfNotCreated($caller);			
			$this->autoFillEmptyNonMandatoryFields();
		}

		/**
		 * @since v1.0.0
		 */
		private function logLevels(): array {
			return [
				LogLevel::EMERGENCY,
			    LogLevel::ALERT,
			    LogLevel::CRITICAL,
			    LogLevel::ERROR,
			    LogLevel::WARNING,
			    LogLevel::NOTICE,
			    LogLevel::INFO,
			    LogLevel::DEBUG
			];
		}

		/**
		 * @return void
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		private function checkLogLevelValid(string $level, string $caller, int $parameter_index) {
			$log_levels = $this->logLevels();

			if( in_array($level, $log_levels) === false ) {
				throw new InvalidArgumentException(sprintf('Syslog::%s expects parameter %s to be one of the following log levels: %s (bug "%s" given)',
					$caller,
					$parameter_index,
					implode(', ', $log_levels),
					$level
				));
			}
		}

		/**
		 * @throws InvalidArgumentException
		 * @since v1.0.0
		 */
		private function getFormatedLevel(string $level, string $caller, int $parameter_index): string {
			$this->checkLogLevelValid($level, $caller, $parameter_index);

			return strtolower($level);
		}

		/**
   		 * @return void
   		 * @since v1.0.0
		 */
		private function timestamp() {
			$this->requested_at = new DateTime;
		}
	}
?>