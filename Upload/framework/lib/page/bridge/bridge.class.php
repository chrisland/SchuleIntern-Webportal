<?php


class bridge extends AbstractPage {
	
	private $isAdmin = false;
	private $isTeacher = false;
	


	public function __construct() {
		
		parent::__construct(array("Bridge"));
				
		$this->checkLogin();
		
		
	}

	public function execute() {
		


		eval("echo(\"" . DB::getTPL()->get("bridge/index"). "\");");
		
	}

	

	
	
	public static function hasSettings() {
		return true;
	}
	
	public static function getSettingsDescription() {
		//return array();

		$settings = array();
		return $settings;

	}
	
	
	public static function getSiteDisplayName() {
		return 'Bridge';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	}
	
	public static function hasAdmin() {
		return true;
	}
	
	public static function getAdminGroup() {
		return false;
		//return 'Webportal_Mensa_Speiseplan';
	}
	
	public static function getAdminMenuGroup() {
		return 'Schulinformationen';
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fas fa-utensils';
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fas fa-utensils';
	}
	

	public static function displayAdministration($selfURL) {
		 
		// $html = '';
		// eval("\$html = \"" . DB::getTPL()->get("mensa/admin/index") . "\";");
		// return $html;
	}
}


?>