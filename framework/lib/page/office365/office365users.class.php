<?php


class office365users extends AbstractPage {
    private $tenantName = null;
    private $isActive = false;
	
	public function __construct() {
		parent::__construct(array("Office 365"));
		
		$this->checkLogin();
		
		if(DB::getSession()->isTeacher() || DB::getSession()->isPupil()) {
		    //
		}
		else {
		    new errorPage();
		}
		
	}

	public function execute() {
        if(DB::getSession()->isTeacher()) {
            $account = DB::getDB()->query_first("SELECT * FROM office365_accounts WHERE accountIsTeacher=1 AND accountDetailsSet=1 AND accountAsvID='" . DB::getSession()->getTeacherObject()->getAsvID() . "'");
            
            if($account['accountUserID'] != "") {
                $vorhanden = true;
            }
            else $vorhanden = false;
        }
        else {
            $account = DB::getDB()->query_first("SELECT * FROM office365_accounts WHERE accountIsPupil=1 AND accountDetailsSet=1 AND accountAsvID='" . DB::getSession()->getSchuelerObject()->getAsvID() . "'");
            
            if($account['accountUserID'] != "") {
                $vorhanden = true;
            }
            else $vorhanden = false;
        }
        
        eval("DB::getTPL()->out(\"" . DB::getTPL()->get("office365/useraccount") . "\");");
	}
	
	
	
	public static function displayAdministration($selfURL) {
	    
	    $isOffice365Active = DB::getSettings()->getBoolean('office365-active');
	    
	    $tenant = DB::getSettings()->getValue('office365-tenant');
	    $tenantID = DB::getSettings()->getValue('office365-tenant-id');
	    
	    if(!$isOffice365Active) {
	        
	    }
	    else {
	        if($_REQUEST['action'] == 'save') {
	            DB::getSettings()->setValue('office365-schueler-createusers', $_POST['createSchueler']);
	            DB::getSettings()->setValue('office365-lehrer-createusers', $_POST['createLehrer']);
	            
	            DB::getSettings()->setValue('office365-schueler-license1', $_POST['schuelerLicense1']);
	            DB::getSettings()->setValue('office365-schueler-license2', $_POST['schuelerLicense2']);
	            DB::getSettings()->setValue('office365-schueler-license3', $_POST['schuelerLicense3']);
	            
	            DB::getSettings()->setValue('office365-lehrer-license1', $_POST['lehrerLicense1']);
	            DB::getSettings()->setValue('office365-lehrer-license2', $_POST['lehrerLicense2']);
	            DB::getSettings()->setValue('office365-lehrer-license3', $_POST['lehrerLicense3']);
	            
	            DB::getSettings()->setValue('office365-schueler-domain', $_POST['schuelerDomain']);
	            DB::getSettings()->setValue('office365-lehrer-domain', $_POST['lehrerDomain']);
	            
	            header("Location: $selfURL");
	            exit();
	        }
	    }
	    
	    
	    // Lizenzen zusammenfassen
	    $lizenzen = Office365Api::getLicenseStatus();
	    $domains = Office365Api::getAllDomains();
	    
	    $licenseSelect = "";
	    $licenseList = "";
	    
	    for($i = 0; $i < sizeof($lizenzen); $i++) {
	        $licenseList .= "<tr><td>" . $lizenzen[$i]['name'] . "</td><td>" . $lizenzen[$i]['availible'] . "</td><td>" . $lizenzen[$i]['consumed'] . "</td></tr>";
	    }
	    
	    $licenseSelectSchueler1 = self::getLicenseSelectOptions($lizenzen,DB::getSettings()->getValue('office365-schueler-license1'));
	    $licenseSelectSchueler2 = self::getLicenseSelectOptions($lizenzen,DB::getSettings()->getValue('office365-schueler-license2'));
	    $licenseSelectSchueler3 = self::getLicenseSelectOptions($lizenzen,DB::getSettings()->getValue('office365-schueler-license3'));
	    
	    $licenseSelectLehrer1 = self::getLicenseSelectOptions($lizenzen,DB::getSettings()->getValue('office365-lehrer-license1'));
	    $licenseSelectLehrer2 = self::getLicenseSelectOptions($lizenzen,DB::getSettings()->getValue('office365-lehrer-license2'));
	    $licenseSelectLehrer3 = self::getLicenseSelectOptions($lizenzen,DB::getSettings()->getValue('office365-lehrer-license3'));
	    
	    $doSchuelerAccounts = DB::getSettings()->getBoolean('office365-schueler-createusers') ? "selected=\"selected\"" : "";
	    $doLehrerAccounts = DB::getSettings()->getBoolean('office365-lehrer-createusers') ? "selected=\"selected\"" : "";
	    
	    $domainSelectSchueler = self::getDomainSelect($domains, DB::getSettings()->getValue('office365-schueler-domain'));
	    $domainSelectLehrer = self::getDomainSelect($domains, DB::getSettings()->getValue('office365-lehrer-domain'));
	    
	    
	    eval("\$html = \"" . DB::getTPL()->get("office365/users/admin/index") . "\";");
	    
	    return $html;
	    
	}
	
	private static function getDomainSelect($data, $selected = '') {
	    $options = "<option value=\"\"></option>";
	    
	    for($i = 0; $i < sizeof($data); $i++) {
	        $selectedHTML = (($selected != "" && $data[$i] == $selected) ? (" selected=\"selected\"") : (""));
	        $options .= "<option value=\"" . $data[$i] . "\"$selectedHTML>" . $data[$i] . "</option>";
	    }
	      
	    return $options;
	}
	
	private static function getLicenseSelectOptions($data, $selected = '') {
	    $options = "<option value=\"\"></option>";
	    
	    for($i = 0; $i < sizeof($data); $i++) {
	        $selectedHTML = (($selected != "" && $data[$i]['name'] == $selected) ? (" selected=\"selected\"") : (""));
	        $options .= "<option value=\"" . $data[$i]['name'] . "\"$selectedHTML>" . $data[$i]['name'] . "</option>";
	    }
	    
	    return $options;
	}
	
	public static function hasAdmin() {
	    return true;
	}
	
	public static function getAdminMenuIcon() {
	    return 'fa far fa-users';
	}
	
	public static function getAdminMenuGroup() {
	    return "Office 365";
	}
	
	public static function getAdminMenuGroupIcon() {
	    return "fa far fa-file-word";
	}
	
	public static function hasSettings() {
		return false;
	}
	
	public static function getAdminGroup() {
	    return 'Webportal_Office365_Admin';
	}
	
	public static function getSettingsDescription() {
		return array();
	}
	
	
	public static function getSiteDisplayName() {
		return 'Office 365 - Benutzer';
	}
		
	public static function onlyForSchool() {
        return [];
	}
	
	public static function dependsPage() {
	    return ['office365'];
	}

}


?>