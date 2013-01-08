<?php
class Packet {
	
	protected $data = '';
	protected $readByteCursor = 0;
	protected $unpackedPayload = array();
	
	protected $fromAddr = '127.0.0.1';
	protected $fromPort = '0';
	
	protected $toAddr = '127.0.0.1';
	protected $toPort = '0';
	
	public function setFrom($addr, $prt) {
		$this->fromAddr = $addr;
		$this->fromPort = $prt;
	}
	
	public function getFromAddr() {
		return $this->fromAddr;
	}
	
	public function getFromPort() {
		return $this->fromPort;
	}
	
	public function setTo($addr, $prt) {
		$this->toAddr = $addr;
		$this->toPort = $prt;
	}
	
	public function getToAddr() {
		return $this->toAddr;
	}
	
	public function getToPort() {
		return $this->toPort;
	}
	
	public function addByte($data) {
		$this->data .= pack("c", $data);
	}
	
	public function addDouble($data) {
		$this->data .= pack("d", $data);
	}
	
	public function add($data) {
		$this->data .= $data;
	}
	
	public function addString($data, $length, $nullpadded = true) {
		if($nullpadded) {
			$this->data .= pack("a".$length, $data);
		} else {
			$this->data .= pack("A".$length, $data);
		}
	}
	
	public function setByteStream($data) {
		$this->data = $data;
	}
	
	public function getBytes() {
		return $this->data;
	}
	
	public function getSize() {
		return strlen($this->data);
	} 
	
	/**
	 * 
	 * @param $format
	 */
	public function unpack($format) {
		$payload = "";
		if($this->getSize() > 0) {
			$payload = unpack($format, $this->data);
		}
		return $payload;
	}

	
	public function setUnpackedPayload($payload) {
		$this->unpackedPayload = $payload;
	}
	
	
	public function getUnpackedPayload() {
		return $this->unpackedPayload;
	}
	
	
}