<?php


class office365 extends AbstractPage {
    private $tenantName = null;
    private $isActive = false;
	
	public function __construct() {
		parent::__construct(array("Office 365"));
		
		$this->checkLogin();
		
	}

	public function execute() {

	}
	
	
	
	public static function displayAdministration($selfURL) {
	    
	    if($_REQUEST['registerSuccess'] > 0) {
	        DB::getSettings()->setValue('office365-active', true);
	        DB::getSettings()->setValue('office365-tenant-id', $_REQUEST['tenant']);
	        
	        header("Location: $selfURL");
	        exit(0);
	    }

	    $canConnectToOffice365 = DB::getSettings()->getValue('office365-app-id') != "";
	    
	    $isOffice365Active = DB::getSettings()->getBoolean('office365-active');
	    
	    $tenant = DB::getSettings()->getValue('office365-tenant');
	    $tenantID = DB::getSettings()->getValue('office365-tenant-id');
	    
	    if(!$isOffice365Active && DB::getSettings()->getValue('office365-app-id') != "") {
	        if($_REQUEST['action'] == 'connect') {
	            DB::getSettings()->setValue('office365-tenant', $_POST['tenant']);
	            $url = base64_encode(DB::getGlobalSettings()->urlToIndexPHP . str_replace("index.php", "", $selfURL));
	            
	            $url = "https://login.microsoftonline.com/{$_POST['tenant']}/adminconsent?client_id=" . DB::getSettings()->getValue('office365-app-id') . "&state=$url&redirect_uri=https://office365.schule-intern.de/";
	        
	            header("Location: $url");
	            exit(0);
	        }

	        
	    }
	    else {
	        if($_REQUEST['action'] == 'disconnect') {
	            DB::getDB()->query("DELETE FROM settings WHERE settingName LIKE 'office365-%'");
	            
	            
	            header("Location: $selfURL");
	            exit(0);
	        }
	    }
	    
	    eval("\$html = \"" . DB::getTPL()->get("office365/admin/index") . "\";");
	    
	    return $html;
	    
	}
	
	public static function hasAdmin() {
	    return true;
	}
	
	public static function getAdminMenuIcon() {
	    return 'fa fas fa-file-word';
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
		        'name' => 'office365-app-id',
                'titel' => 'AnwendungsID',
                'typ' => 'ZEILE'
            ],
            [
                'name' => 'office365-app-secret',
                'titel' => 'Anwendungsgeheimnis (Secret)',
                'typ' => 'ZEILE'
            ]
        ];
	}
	
	
	public static function getSiteDisplayName() {
		return 'Office 365';
	}
		
	public static function onlyForSchool() {
        return [];
	}

}


?>