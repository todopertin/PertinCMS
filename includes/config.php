<?php
	ob_start();
	session_start();
	
	//database credentials
	define('DBHOST', 127.0.0.1);
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBNAME', 'blog1');
	
	//setting up a PDO connection to database
	$dbConn=new PDO('mysql: host='.DBHOST.';dbname='.DBNAME, DBUSER, DBPASS);
	$dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbConn->setAttribute(PDO::ATTR_EMULATE_PREPARES => false);
	
	date_default_timezone_set('Europe/London');
	
	function __autoload($class){
		$classPath='/classes/class'.$class.'.php';
		if(file_exists($classPath)){
			require_once $classPath;
		}
		$classPath='../classes/class'.$class.'.php';
		if(file_exists($classPath)){
			require_once $classPath;
		}
		$classPath='../../classes/class'.$class.'.php';
		if(file_exists($classPath)){
			require_once $classPath;
		}
	}
	
	$user=new User($dbConn);
?>
