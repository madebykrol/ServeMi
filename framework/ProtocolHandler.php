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
	
	/**
	 * set the command size in bytes.
	 * @param unknown_type $size
	 */
	public function setCommandSize($size) {
		$this->commandSize = $size;
	}
	
	/**
	 * Set a listener.
	 * @param PacketListener $l
	 */
	public function setPacketListener(PacketListener $l) {
		$this->listener = $l;
	}
	
	/**
	 * Add a command
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
		if($this->unpackPacket($packet)) {
			try {
				return $this->listener->onIncoming($packet);
			} catch (Exception $e) {
				$this->listener->onError($e);
			}
			return false;
		} else {
			throw new BadPacketException();
		}
		return false;
	}
	
	/**
	 * unpack the packet by it's format from the command list.
	 * @param Packet $packet
	 */
	protected function unpackPacket(Packet $packet) {
		$payload = $packet->unpack(Server::BYTE."command");
		if(is_array($payload)) {
			$command = $payload["command"];
			$pattern = Server::BYTE."command";
			if(isset($this->commands[$command])) {
				
				foreach($this->commands[$command] as $pktPart) {
					$datatype 	= $pktPart[0];
					$ident			= $pktPart[1];
					$len = "";
					if(isset($pktPart[2])) {
						$len = $pktPart[2];
					}
					$pattern .= "/".$datatype.$len.$ident;
					
				}
				
				$packetPayload = $packet->unpack($pattern);
				
				if((count($packetPayload)-1) == count($this->commands[$command])) {
					$packet->setUnpackedPayload($packetPayload);
					return true;
				}
			} 
		}
		return false;
	}
}