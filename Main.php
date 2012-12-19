<?php
class Main extends Server {
	
	protected function init() {
		$this->hosts[] 		= array('127.0.0.1', '1223');
		$this->domain 		= Server::DOMAIN_INET;
		$this->type 			= Server::TYPE_DGRAM;
		$this->protocol 	= Server::PROTOCOL_UDP;
	}
	
	
	public function serverLoop() {
		$datagram = new Datagram($this->socket);
		
		if($datagram->read(1024)) {
			$response = $datagram->getBuffer()."=>Response";
		}
		
		$len = strlen($response);
		
		print $response."\n";
		
		$datagram->send($response, $datagram->getHost(), $datagram->getPort());

	}
	
}