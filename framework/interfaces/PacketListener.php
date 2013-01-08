<?php
interface PacketListener {
	/**
	 * Running after the packet has been validated
	 * This method should return true if packet is supposed to be put in the package queue.
	 * @param Packet $packet
	 * @param unknown_type $command
	 * @return boolean
	 */
	public function onIncoming(Packet $packet);
	
	public function onError(Exception $e);
}