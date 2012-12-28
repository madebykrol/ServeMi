<?php
interface PacketListener {
	public function process(Packet $packet, $command);
}