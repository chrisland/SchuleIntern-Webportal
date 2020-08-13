<?php




class impressum extends AbstractPage {
	public function __construct() {
		parent::__construct(array("Impressum"));
	}

	public function execute() {
		$impressumText = DB::getSettings()->getValue("impressum-text");
		
		eval("DB::getTPL()->out(\"" . DB::getTPL()->get("impressum/index") . "\");");
		PAGE::kill(true);
			//exit(0);
	}

	public static function getSettingsDescription() {
		$settings = array();
		
		$settings[] = array(
				'name' => 'impressum-text',
				'typ' => 'TEXT',
				'titel' => "Impressum",
				'text' => 'Text des Impressums'
		);
		
		return $settings;
	}
	
	public static function getSiteDisplayName() {
		return "Impressum";
	}
	
	public static function hasSettings() {
		return false;
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	
	}
	
	public static function siteIsAlwaysActive() {
		return true;
	}
	
	public static function hasAdmin() {
		return true;
	}
	
	public static function getAdminGroup() {
		return "Webportal_Impressum";
	}

	public static function displayAdministration($selfURL) {
		switch($_REQUEST['action']) {
			case "save":
				DB::getSettings()->setValue("impressum-text", $_POST['impressumText']);
				
				header("Location: $selfURL&saved=1");
				exit(0);
			
			default:
				$text = DB::getSettings()->getValue("impressum-text");
				
				if(!DB::getSettings()->getBoolean("impressum-migrate")) {
					$text = str_replace("\n", "<br />", $text);
					DB::getSettings()->setValue("impressum-text", $text);
					DB::getSettings()->setValue("impressum-migrate", "1");
					header("Location: $selfURL");
				}
				
				$html = "";
				
				eval("\$html .= \"" . DB::getTPL()->get("impressum/admin/index") . "\";");
				
				return $html;
		}
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fa-info-circle';
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fa-info-circle';
	}
	
	public static function getAdminMenuGroup() {
		return 'Schulinformationen';
	}
}


?>