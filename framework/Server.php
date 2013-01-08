<?php
abstract class Server {
	
	protected $startUTime = 0;
	
	protected $lastTickUTime = 0;
	
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
	 * 
	 */
	protected $protocolHandler = null;
	
	/**
	 * Protocol
	 * @var int
	 */
	protected $transportProtocol = Server::PROTOCOL_TCP;
	
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
	 * Stack of packets 
	 */
	protected $packetStack = null;
	protected $responseStack = null;
	
	protected $stackSize = 30;
	
	protected $tickIntervalUS = 300;
	
	protected $maxPacketSize = 1024;
	
	/**
	 * Server constructor
	 */
	public function __construct() {
		
		$this->protocolHandler = new ProtocolHandler();
		$this->packetStack = new Stack("Packet", $this->stackSize);
		$this->responseStack = new Stack("Packet");
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
		$this->lastTickUTime = microtime(true);
		while($this->isRunning()) {
			
			$datagram = new Datagram($this->socket);
			
			if($datagram->read($this->maxPacketSize)) {
				try {
					$pkt = $datagram->getPacket();
					if($this->protocolHandler->processPacket($pkt)) {
						$this->packetStack->add($pkt);
					}
				} catch (BadPacketException $e) {
					
				}
			}
			if($this->lastTickUTime < (microtime(true)-($this->tickIntervalUS * 0.000001))) {
  			$this->simulationStep();
  			$this->sendOutgoing();
  			$this->lastTickUTime = microtime(true);
			} 
  		
		}
  
	}
	public function isRunning() {
		return $this->running;
	}
	
	public function shutdown() {
		print "shutdown";
	}
	
	
	
	public abstract function simulationStep();
	
	
	protected function queueOutgoing(Packet $pkt) {
		$this->responseStack->add($pkt);
	}
	
	protected function sendOutgoing() {
		$datagram =  new Datagram($this->socket);
		foreach($this->responseStack as $index => $packet) {
			$datagram->send($packet, $packet->getToAddr(), $packet->getToPort());
		}
	}
	
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
	
	const BYTE = "C1";
	const SBYTE = "c1";
	
	/**
	 * Little endian byte order
	 * @var unknown_type
	 */
	const DOUBLE = "d";
	
	/**
	 * Null padded String
	 * @var unknown_type
	 */
	const STRING = "a";
	
	const H_HEX = "H";
	const L_HEX = "h";
	
}