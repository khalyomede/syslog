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
		 */
		public function debug(string $message, array $context = []): Syslog {
			$this->createSocketIfNotCreatedYet('debug');

			$log = "<34>1 2018-03-30T01:04:00.003Z mymachine.example.com su - ID47 - BOM'su root' failed for lonvick on /dev/pts/8";

			$this->send($log);

			return $this;
		}

		public function log(string $level, string $message, array $context = []): Syslog {

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

			if( filter_var($normalized_url, FILTER_VALIDATE_URL) === false ) {
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

		private function createSocketIfNotCreatedYet(string $caller) {
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
		private function send(string $syslog) {
			$success = socket_sendto($this->socket, $syslog, strlen($syslog), MSG_DONTROUTE, $this->host, $this->port);

			if( $success === false ) {
				throw new RuntimeException(sprintf('Syslog::%s ecountered an error during the binding of the host to the socket (detail: %s)', 
					$caller,
					socket_strerror(socket_last_error())
				));
			}
		}
	}
?>