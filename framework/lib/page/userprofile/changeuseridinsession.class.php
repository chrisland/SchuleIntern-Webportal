<?php


class changeuseridinsession extends AbstractPage {
	
	public function __construct() {		
		parent::__construct(array("Benutzerprofil", "Als anderer Benutzer anmelden"));
		
		die("NOT OK");		
	}
	
	public function execute() {
		die("NOT OK");
	}
	
	public static function hasAdmin() {
		return true;
	}
	
	public static function displayAdministration($selfURL) {
		
		if($_GET['changeIt'] > 0) {
		    
		    if(intval($_POST['newUserID']) > 1) {     // Nicht als Spitschka_Admin anmelden
		        
		        $user = user::getUserByID(intval($_POST['newUserID']));
		        if($user != null) {
		        
    		        Fremdlogin::createFremdlogin($user, $_POST['message']);
    		        
    			    DB::getDB()->query("UPDATE sessions SET sessionIsDebug=1, sessionUserID='" . intval($_POST['newUserID']) . "' WHERE sessionID='" . DB::getSession()->getSessionID() . "'");
    			    header("Location: index.php");
    			    exit(0);
		        }
		        else {
		            new errorPage();
		        }
		    }
		}
		
		// Alle User laden
		
		$users = DB::getDB()->query("SELECT * FROM users ORDER BY userNetwork, userName, userLastName, userFirstName");
		
		$select = "";
		while($u = DB::getDB()->fetch_array($users)) {
		    if($u['userID'] > 1)
			$select .= "<option value=\"" . $u['userID'] . "\">" . $u['userLastName'] . ", " . $u['userFirstName'] . " (" . $u['userName'] . ") - " . $u['userNetwork'] . "</option>";
		}
		
		eval("\$html = \"" . DB::getTPL()->get("userprofile/changeuserid") . "\";");
		return $html;
	}
	
	
	public static function getNotifyItems() {
		return array();
	}
	
	public static function hasSettings() {
		return false;
	}
	
	/**
	 * Stellt eine Beschreibung der Einstellungen bereit, die für das Modul nötig sind.
	 * @return array(String, String)
	 * array(
	 * 	   array(
	 * 		'name' => "Name der Einstellung (z.B. formblatt-isActive)",
	 *		'typ' => ZEILE | TEXT | NUMMER | BOOLEAN,
	 *      'titel' => "Titel der Beschreibung",
	 *      'text' => "Text der Beschreibung"
	 *     )
	 *     ,
	 *     .
	 *     .
	 *     .
	 *  )
	 */
	public static function getSettingsDescription() {
		return array();
	}
	
	
	public static function getSiteDisplayName() {
		return 'Benutzer wechseln';
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fas fa-sync-alt';
	}
	
	public static function getAdminMenuGroup() {
		return 'Debugging';
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fa-fire-extinguisher';
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

}


?>