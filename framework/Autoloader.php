<?php
class Autoloader {
	
	protected $paths = array(
			'src/',
	);
	
	
	
	/**
	 * To speed up the application we pre-register some classes in the Autoloader so
	 * that when they are loaded we don't have to do recursive lookup to find them.
	 * @var unknown_type
	 */
	protected $registeredClassPaths = array(
			'Server' 					=> 'framework/',
			'Datagram' 				=> 'framework/',
			'ProtocolHandler' => 'framework/',
			'Packet'					=> 'framework/',
			'ProtocolListner' => 'framework/',
			'Stack'						=> 'framework/',
			
			'PacketListener'	=> 'framework/interfaces/',
		
			'Main' 			=> '',
			
			'ClassNotFoundException' 	=> 'framework/exceptions/',
			'SocketException' 				=> 'framework/exceptions/',
			'BadPacketException'			=> 'framework/exceptions/',
			
	);
	
	/**
	 * Auto load class from one of $this->paths
	 * @param string $class
	 */
	public /* void */ function load ($class) {
	
		$trail = '';
		$found = false;
	
		if (isset($this->registeredClassPaths[$class])) {
			include($this->registeredClassPaths[$class].$class.".php");
			return true;
		}
		
		foreach ($this->paths as $path) {
			if (is_file($path.$class.'.php')) {
				include($path.$class.'.php');
				return true;
			}
		}

		throw new ClassNotFoundException("Class not found exception $class");
	}
	
}