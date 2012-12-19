<?php
$sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

$msg = "Asdf";
$len = strlen($msg);

socket_sendto($sock, $msg, $len, 0, '127.0.0.1', 1223);

$buf = '';
$from = '';
// will block to wait server response
$bytes_received = socket_recvfrom($sock, $buf, 65536, 0, $from, $port);
if ($bytes_received == -1)
	die('An error occured while receiving from the socket');
echo "Received $buf from ".$from.":".$port."\n";