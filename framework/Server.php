<?php
abstract class Server {
	
	protected $clients = array();
	
	/**
	 * instance running
	 * @var Boolean 
	 */
	protected $running = false;
	
	/**
	 * Hosts
	 * @var array
	 */
	protected $hosts = array();
	

	
	/**
	 * Protocoll
	 * @var int
	 */
	protected $transportProtocoll = Server::PROTOCOL_TCP;
	
	/**
	 * Socket
	 * @var handler
	 */
	protected $socket = null;
	
	/**
	 * Domain
	 * @var int
	 */
	protected $domain = Server::DOMAIN_INET;
	
	/**
	 * Type
	 * @var int
	 */
	protected $type = Server::TYPE_STREAM;
	
	/**
	 * 
	 */
	public function __construct() {
		
		$this->init();
		
		if(($this->socket = socket_create($this->domain, $this->type, $this->protocol)) === false) {
			throw new SocketException(socket_last_error());
		} 
		
		foreach($this->hosts as $host) {
			
			$port = null;
			if(isset($host[1])) {
				$port = $host[1];
			}

			$host = $host[0];
			
			if(!socket_bind($this->socket, $host, $port)) {
				throw new SocketException(socket_last_error($this->socket));
			}
		}

		
		$this->running = true;
	}
	
	public function start() {
		print "start";
	}
	
	public function isRunning() {
		return $this->running;
	}
	
	public function shutdown() {
		print "shutdown";
	}
	
	public abstract function serverLoop();
	
	
	protected abstract function init();
	
	const PROTOCOL_UDP 		= SOL_UDP;
	const PROTOCOL_TCP 		= SOL_TCP;
	
	const DOMAIN_INET 		= AF_INET;
	const DOMAIN_INET6 		= AF_INET6;
	const DOMAIN_UNIX 		= AF_UNIX;
	
	const TYPE_STREAM 		= SOCK_STREAM;
	const TYPE_DGRAM 			= SOCK_DGRAM;
	const TYPE_SEQPACKET 	= SOCK_SEQPACKET;
	const TYPE_RAW 				= SOCK_RAW;
	const TYPE_RDM 				= SOCK_RDM;
	
}