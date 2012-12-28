<?php
class Main extends Server implements PacketListener{
	
	protected $pClients = array();
	
	protected function init() {
		
		$this->protocolHandler->setPacketListener($this);
		
		$this->protocolHandler->setCommandSize(2);
		$this->protocolHandler->addCommand(0x00, array(array("string", 4)));
		$this->protocolHandler->addCommand(0x01, array(array("string", 100), array('string', 15), array("int", 32)));
		$this->protocolHandler->addCommand(0x02, array(array(Server::DOUBLE, "lat"), array(Server::DOUBLE, "lng")));
		$this->protocolHandler->addCommand(0xff, array(array("")));
		
		
		$this->hosts[] 		= array('127.0.0.1', '1223');
		$this->domain 		= Server::DOMAIN_INET;
		$this->type 			= Server::TYPE_DGRAM;
		$this->protocol 	= Server::PROTOCOL_UDP;
	}
	
	
	public function serverLoop() {
		
		
		$datagram = new Datagram($this->socket);
		
		if($datagram->read(106)) {
			$pkt = $datagram->getPacket();
		}
		
		$responsePacket = new Packet();
		try {
			$responsePacket = $this->protocolHandler->processPacket($pkt);
		} catch(BadPacketException $e) {
			$responsePacket->addData("Bad packet exception LOOOL");
		}
		
		
		
		
		$len = $pkt->getSize();
		
		print "Recieved: ".$pkt->getByteStream()." of lenght: ".$len."\n";
		
		
		$datagram->send($responsePacket, $datagram->getHost(), $datagram->getPort());

	}
	
	public function process(Packet $packet, $command) {
		
		$ptk = new Packet();
		$ptk->addData("Clean package LOL");
		
		return $ptk;
	}
	
	
	protected function readIncomming() {
		
	}
	
	
	
}