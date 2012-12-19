<?php
class Datagram {
	
	/**
	 * Socket handler
	 * @var resource
	 */
	protected $socket;
	
	/**
	 * Databuffer
	 * @var String
	 */
	protected $buffer;
	
	/**
	 * Read length in bytes
	 * @var int
	 */
	protected $length;
	
	/**
	 * flags
	 * @var int
	 */
	protected $flags;
	
	/**
	 * Hostname of sender/receiver
	 * @var string
	 */
	protected $host;
	
	/**
	 * Port from sender/receiver
	 */
	
	protected $port;
	
	/**
	 * 
	 * @param unknown_type $socket
	 * @param unknown_type $len
	 * @param unknown_type $flags
	 */
	
	public function __construct($socket) {
		
		$this->socket 	= $socket;

		
	}
	
	public function read($len, $flags = 0) {
		
		$this->length 	= $len;
		$this->flags 		= $flags;
		
		$buffer = "";
		$bytesReceived = socket_recvfrom($this->socket, $buffer, $this->length, $this->flags, $this->host, $this->port);
		if($bytesReceived == -1) {
			throw new SocketException(socket_last_error($this->socket));
		} else {
			$this->buffer .= $buffer. $this->port;
			return true;
		}
		
		return false;
		
	}
	
	public function send($msg, $host, $port, $flags = 0) {
		
		$len = strlen($msg);

		socket_sendto($this->socket, $msg, $len, $flags, $host, $port);
		
	}
	
	public function getHost() {
		return $this->host;
	}
	
	
	public function getPort() {
		return $this->port;
	}
	
	public function getBuffer() {
		return $this->buffer;
	}
	
	public function flushBuffer() {
		$this->buffer = "";
	}
	
	const FLAG_OOB 				= MSG_OOB;
	const FLAG_PEEK 			= MSG_PEEK;
	const FLAG_WAITALL 		= MSG_WAITALL;
	const FLAG_DONTWAIT 	= MSG_DONTWAIT;
}