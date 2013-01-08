<?php
class Stack implements Iterator{ 
	
	
	private $position = 0;
	
	private $stack = array();
	private $T;
	private $size;
	
	public function __construct($T, $size = null) {
		$this->T = $T;
		$this->size = $size;
		$this->position = 0;
	} 
	
	public function put($item, $index = 0) {
		if($item instanceof $this->T) {
			$this->stack[$index] = $item;
		}
	}
	
	public function get($index) {
		return $this->stack[$index] = $item;
	}
	
	public function add($item) {
		if($item instanceof $this->T) {
			$this->stack[] = $item;
		}
	}
	
	public function pop() {
		
		
	}
	
	public function rewind() {
		
	}
	
	public function current() {
		$item = $this->stack[$this->position];
		unset($this->stack[$this->position]);
		return $item;
	}
	
	public function key() {
		return $this->position;
	}
	
	public function next() {
		++$this->position;
	}

	
	public function valid() {
		return isset($this->stack[$this->position]);
	}
	
}