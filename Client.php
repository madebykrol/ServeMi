<?php
include('framework/Autoloader.php');

$autoloader = new Autoloader();
// Use autoloader
spl_autoload_register(array($autoloader, 'load'));


$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

$msg = 0xFF;
$msg = new Packet();
$msg->addData(pack("c", 0x02)); // set handshake command
$msg->addData(pack("d", 36.00000009)); // protocol version
$msg->addData(pack("d", 91.99282884));

$datagram = new Datagram($socket);
$datagram->send($msg, '127.0.0.1', '1223');


$buf = '';
$from = '';

// will block to wait server response
$bytes_received = socket_recvfrom($socket, $buf, 40, 0, $from, $port);
if ($bytes_received == -1)
	die('An error occured while receiving from the socket');
echo "Received $buf from ".$from.":".$port."\n";