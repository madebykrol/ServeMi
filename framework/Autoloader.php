<?php
class Autoloader {
	
	/**
	 * To speed up the application we pre-register some classes in the Autoloader so
	 * that when they are loaded we don't have to do recursive lookup to find them.
	 * @var unknown_type
	 */
	protected $registeredClassPaths = array(
			'Server' 		=> 'framework/',
			'Datagram' 	=> 'framework/',
		
			'Main' 			=> '',
			
			'ClassNotFoundException' 	=> 'framework/exceptions/',
			'SocketException' 				=> 'framework/exceptions/',
			
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

		throw new ClassNotFoundException("Class not found exception $class");
	}
	
}