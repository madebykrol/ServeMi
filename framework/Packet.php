<?php
class Packet {
	
	protected $data = "";
	protected $readByteCursor = 0;
	
	public function addData($data) {
		$this->data .= $data;
	}
	
	public function setByteStream($data) {
		$this->data = $data;
	}
	
	public function getByteStream() {
		return $this->data;
	}
	
	public function getSize() {
		return strlen($this->data);
	} 
	
	/**
	 * 
	 * @param $format
	 */
	public function unpack($format) {
		$byteArray = unpack($format, $this->data);
		return $byteArray;
	}

	
	
	
}