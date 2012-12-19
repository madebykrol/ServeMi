<?php
include('framework/Autoloader.php');

$autoloader = new Autoloader();
// Use autoloader
spl_autoload_register(array($autoloader, 'load'));


$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

$msg = "Asdf";
$len = strlen($msg);

$datagram = new Datagram($socket);
$datagram->send($msg, '213.180.83.78', '1223');


$buf = '';
$from = '';
// will block to wait server response
$bytes_received = socket_recvfrom($socket, $buf, 65536, 0, $from, $port);
if ($bytes_received == -1)
	die('An error occured while receiving from the socket');
echo "Received $buf from ".$from.":".$port."\n";