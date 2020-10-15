<?php


//include_once( FOLDER_ROOT.'/lib/helper/database.php');

class Factory {
	
	private static $db = false;
	
	public static function getDb() {
		
		
		if (!self::$db) {
			
			$config = (object)Config::get();

			// Database
			// if ( isset($config->dbHost) && $config->dbHost
			// 		&& isset($config->dbUser) && $config->dbUser
			// 		&& isset($config->dbPass)
			// 		&& isset($config->dbName) && $config->dbName ) {		

				self::$db = new Database($config->dbHost, $config->dbUser, $config->dbPass, $config->dbName);
				self::$db->connect();
					
			// }
			
		}
		return self::$db;
				
	}
	

}
?>