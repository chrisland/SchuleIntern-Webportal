<?php


class office365info extends AbstractPage {
	
	public function __construct() {
		parent::__construct(array("Office 365"));
		
		$this->checkLogin();
		
	}

	public function execute() {

	    $loginName = "";
	    
	    if(DB::getSession()->isTeacher() && DB::getSettings()->getValue("office365-info-teacherDomain") != "") {
	        $loginName = DB::getSession()->getUser()->getUserName() . "@" . DB::getSettings()->getValue("office365-info-teacherDomain");
	    }
	    
	    if(DB::getSession()->isPupil() && DB::getSettings()->getValue("office365-info-pupil-domain") != "") {
	        $loginName = DB::getSession()->getUser()->getUserName() . "@" . DB::getSettings()->getValue("office365-info-pupil-domain");
	    }
	    
	    
	    eval("DB::getTPL()->out(\"" . DB::getTPL()->get("office365/useraccountinfo") . "\");");
	    
	}
	
	
	
	public static function displayAdministration($selfURL) {
	   return '';
	}
	
	public static function hasAdmin() {
	    return true;
	}
	
	public static function getAdminMenuIcon() {
	    return 'fa far fa-key';
	}
	
	public static function getAdminMenuGroup() {
	    return "Office 365";
	}
	
	public static function getAdminMenuGroupIcon() {
	    return "fa far fa-file-word";
	}
	
	public static function hasSettings() {
		return true;
	}
	
	public static function getAdminGroup() {
	    return 'Webportal_Office365_Admin';
	}
	
	public static function getSettingsDescription() {
		return [
		    [
		      'name' => 'office365-info-teacherDomain',
		      'titel' => 'Login Domäne für Lehrer',
		      'typ' => 'ZEILE',
		      'text' => 'z.b. rs-testschule.de'
	        ],
		    
		    [
		        'name' => 'office365-info-pupil-domain',
		        'titel' => 'Login Domäne für Schüler',
		        'typ' => 'ZEILE',
		        'text' => 'z.b. schueler.rs-testschule.de'
		    ]
		];
		
	}
	
	
	public static function getSiteDisplayName() {
		return 'Office 365 Logindaten';
	}
		
	public static function onlyForSchool() {
        return [];
	}

}


?>