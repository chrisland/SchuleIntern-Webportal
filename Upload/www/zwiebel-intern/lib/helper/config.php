<?php


class Config {
	
	private static $_ = array();
	
	
	public static function set($key, $val = null) {
		
		if (!$key) {
			return false;
		}

		self::$_[$key] = $val;
		return true;
	}
	
	
	public static function get($key = false) {
		if ($key) {
			if ( self::$_[$key] ) {
				return self::$_[$key];
			}
			return false;
		}
		return self::$_;
	}
	
}
?>