<?php
include('framework/Autoloader.php');

$autoloader = new Autoloader();
// Use autoloader
spl_autoload_register(array($autoloader, 'load'));


$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

$msg = new Packet();
$msg->addByte(0x02); // Ident
$msg->addString("mbk", 24);
$msg->add(md5("mbk"."AsdfGH", true));

$datagram = new Datagram($socket);
$datagram->send($msg, '127.0.0.1', '1223');

$ident = null;
$moved = true;
while(true) {
	$buf = '';
	$from = '';
	
	$datagram = new Datagram($socket);
	$datagram->read(100);
	$pkt = $datagram->getPacket();
	
	$payload = $pkt->unpack(Server::BYTE."command");
	$command = "";
	if(is_array($payload)) {
		$command = $payload['command']; 
	}
	
	if($command == 0x02) {
		$payload = $pkt->unpack(Server::BYTE."command/".Server::STRING."32ident");
		$ident = $payload['ident'];
	}
	
	

	
	if(isset($ident)) {
		handleIncomming($ident, $command, $pkt);
		if($moved) {
			sendPlayerPosition($ident);
			$moved = false;
		}
	}
}

function sendPlayerPosition($ident) {
	$pos = new Packet();
	$pos->addByte(0x03);
	$pos->add($ident);
	$pos->addDouble('61.9912');
	$pos->addDouble('31.1221');
	$pos->addDouble('1.0');
	
	global $socket;
	$datagram = new Datagram($socket);
	$datagram->send($pos, '127.0.0.1', '1223');
	print "Updated player pos\n";
	
}


function handleIncomming($ident, $command, Packet $pkt) {
	if($command == 0x01) {
		$heartbeat = new Packet();
		$heartbeat->addByte(0x01);
		$heartbeat->add($ident);
		
		global $socket;
		$datagram = new Datagram($socket);
		$datagram->send($heartbeat, '127.0.0.1', '1223');
		print "Pong!\n";
	}
}

