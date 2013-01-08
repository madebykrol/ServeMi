<?php
class Main extends Server implements PacketListener{
	
	protected $pClients = array();
	protected $salt = "AsdfGH";
	
	protected function init() {
		
		$this->protocolHandler->setPacketListener($this);
		
		$this->protocolHandler->setCommandSize(2);
		$this->protocolHandler->addCommand(0x01, array(array(Server::STRING, 'ident', 32)));
		$this->protocolHandler->addCommand(0x02, array(array(Server::STRING, 'username', 24), array(Server::H_HEX, 'key', 32)));
		$this->protocolHandler->addCommand(0x03, array(array(Server::STRING, 'ident', 32), array(Server::DOUBLE, "lat"), array(Server::DOUBLE, "lng"), array(Server::DOUBLE, "alt")));
		$this->protocolHandler->addCommand(0xff, array(array("")));
		
		$this->hosts[] 		= array('0.0.0.0', '1223');
		$this->domain 		= Server::DOMAIN_INET;
		$this->type 			= Server::TYPE_DGRAM;
		$this->protocol 	= Server::PROTOCOL_UDP;
		
	}
	
	
	public function simulationStep() {
		
		foreach($this->packetStack as $index => $packet) {
			$payload = $packet->getUnpackedPayload();
			switch($payload['command']) {
				case 0x01 :
					$ident = $payload['ident'];
					$this->pClients[$ident]->resetHeartbeatTries();
					break;
					
				case 0x02 :
					
					$ident = $this->identPlayer($payload, $packet);
					
					break;
				
					
				case 0x03 :
					$ident = $payload['ident'];
					$lat = $payload['lat'];
					$lng = $payload['lng'];
					$alt = $payload['alt'];
					
					$this->updatePlayerPosition($this->pClients[$ident], $lat, $lng, $alt);
					
					break;
			}
		}
		
		foreach($this->pClients as $ident => $player) {
			$this->heartbeat($player);
		}

	}
	
	public function onIncoming(Packet $packet) {
		$payload = $packet->getUnpackedPayload();
		switch($payload['command']) {
			case 0x02 :
				
				$key 	= md5($payload['username'].$this->salt);
				$uKey	= $payload['key'];
				
				if($key == $uKey) {
					return true;
				} else {
					
					throw new InvalidKeyException("error message", $packet);
					return false;
					
				}
				break;
		}
		
		return true;
	}
	
	public function onError(Exception $e) {
		print $e->getMessage();
	}
	
	protected function identPlayer($payload, Packet $packet) {
		$host = $packet->getFromAddr();
		$port = $packet->getFromPort();
		$ident = md5($payload['username'].count($this->pClients).$host.$port);
		
		$player = new Player($ident, $packet->getFromAddr(), $packet->getFromPort());
		$this->pClients[$ident] = $player;
		
		$pkt = new Packet();
		$pkt->setTo($packet->getFromAddr(), $packet->getFromPort());
		$pkt->addByte(0x02); // Ident
		$pkt->add($ident);
		$this->queueOutgoing($pkt);
		
		return $ident;
	}
	
	protected function updatePlayerPosition(Player $player, $lat, $lng, $alt) {
		$player->setPhysPosition($lat, $lng, $alt);
		print "playerpos: ".$lat.", ".$lng.", ".$alt;
	}
	
	protected function heartbeat(Player $player) {
		$ident = $player->getIdent();
		if($player->getHeartbeatTries() >= 3) {
			unset($this->pClients[$ident]);
			print "Client '".$ident."' has disconnected";
		}
		if($player->lastHeartBeat() >= 3) {
			print "Ping?\n";
			$hPkt = new Packet();
			$hPkt->setTo($player->getIP(), $player->getPort());
			$hPkt->addByte(0x01);
			$hPkt->add(pack(Server::BYTE, 'ping'));
			$this->queueOutgoing($hPkt);
			$player->setLastHeartBeat(time());
			$player->addHeartbeatTry();
		}
	}

	
}
