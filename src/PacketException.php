<?php
class PacketException extends Exception {
	
	protected $packet = null;
	
	public function __construct($message, Packet $pkt, $code = null) {
		parent::__construct($message, $code);
		$this->packet = $pkt;
	}
	
	public function getPacket() {
		return $this->packet;
	}
}