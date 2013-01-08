<?php
class Player {
	
	protected $ip;
	protected $port;
	protected $heartbeatTime;
	protected $heartbeatTries = 0;
	protected $ident;
	
	protected $physPos = array('lat' => '', 'lng' => '', 'alt' => '');
	
	public function __construct($ident,  $ip, $port) {
		$this->ip = $ip;
		$this->port = $port;
		$this->ident = $ident;
	}
	
	public function setIdent($ident) {
		$this->ident = $ident;
	}
	
	public function getIdent() {
		return $this->ident;
	}
	
	public function getIP() {
		return $this->ip;
	}
	
	public function getPort() {
		return $this->port;
	}
	
	public function lastHeartBeat() {
		return time()-$this->heartbeatTime;
	}
	
	public function setLastHeartbeat($time) {
		$this->heartbeatTime = $time;
	}

	public function getHeartbeatTries() {
		return $this->heartbeatTries;
	}
	public function addHeartbeatTry() {
		$this->heartbeatTries++;
	}
	
	public function resetHeartbeatTries() {
		$this->heartbeatTries = 0;
	}
	
	public function setPhysPosition($lat, $lng, $alt) {
		$this->physPos['lat'] = $lat; 
		$this->physPos['lng'] = $lng;
		$this->physPos['alt'] = $alt;
	}
}