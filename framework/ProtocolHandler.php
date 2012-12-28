<?php
class ProtocolHandler {
	protected $commands = array();
	
	/**
	 * @var ProtocolListener
	 */
	protected $listener = null;
	
	/**
	 * number of bytes that the command size is.
	 * @var int
	 */
	protected $size = 2;
	
	public function __construct() {
		
	}
	
	public function setCommandSize($size) {
		$this->commandSize = $size;
	}
	
	public function setPacketListener(PacketListener $l) {
		$this->listener = $l;
	}
	
	/**
	 * Add a comman
	 * @param unknown_type $command
	 */
	public function addCommand($command, $format) {
		$this->commands[$command] = $format; 
	}
	
	
	/**
	 * processing a datapacket firing off any callbacks from listeners 
	 * @param Packet $packet
	 * @throws BadPacketException
	 * @return Packet
	 */
	public function processPacket(Packet $packet) {
		if($this->validatePacket($packet)) {
			return $this->listener->process($packet, "");
		} else {
			throw new BadPacketException();
		}
		return null;
	}
	
	public function validatePacket(Packet $packet) {
		$command = $packet->unpack(Server::BYTE);
		
		$command = $command[1];
		$pattern = Server::BYTE."command";
		if(isset($this->commands[$command])) {
			
			foreach($this->commands[$command] as $pktPart) {
				$datatype 	= $pktPart[0];
				$ident			= $pktPart[1];
				$pattern .= "/".$datatype.$ident;
			}
			
			$packetPayload = $packet->unpack($pattern);
			print_r($packetPayload);
			if((count($packetPayload)-1) == count($this->commands[$command])) {
				return true;
			}
		} 
		
		return false;
	}
}