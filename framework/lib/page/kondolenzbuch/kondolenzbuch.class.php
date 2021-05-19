<?php


class kondolenzbuch extends AbstractPage {
	
    private $canDelete = false;
    
	public function __construct() {		
		parent::__construct(array("Kondolenzbuch"));
		
		$this->checkLogin();
	}

	public function execute() {
		
	    $canDelete = false;
	    
	    if(schulinfo::isSchulleitung(DB::getSession()->getUser())) $this->canDelete = true;
	    
	    if(DB::getSession()->isAdmin()) $this->canDelete = true;
	    
	    if(DB::getSession()->isMember(self::getAdminGroup())) $this->canDelete = true;
	    
	    
	    switch($_GET['action']) {
	        case 'add':
	            $this->addEntry();
	        break;
	        
	        case 'delete':
	            $this->deleteEntry();
	        break;
	        
	        default:
	            $this->showBook();
	        break;
	    }
	    
	    
	    
	}
	
	private function deleteEntry() {
	    DB::getDB()->query("DELETE FROM kondolenzbuch WHERE eintragID='" . DB::getDB()->escapeString($_GET['eintragID']) . "'");
	    header("Location: index.php?page=kondolenzbuch");
	    exit(0);
	    
	}
	
	private function addEntry() {
	    DB::getDB()->query("INSERT INTO kondolenzbuch (eintragName, eintragText, eintragTime) VALUES ('" . DB::getDB()->escapeString($_POST['name']) . "','" . DB::getDB()->escapeString($_POST['text']) . "',UNIX_TIMESTAMP())");
	
	    header("Location: index.php?page=kondolenzbuch");
	    exit(0);
	
	}
	
	
	private function showBook() {
	    

	    
	    
	    $entries = DB::getDB()->query("SELECT * FROM kondolenzbuch ORDER BY eintragTime DESC");
	   
	    
	    $entriesHTML = "";
	    
	    
	    while($e = DB::getDB()->fetch_array($entries)) {
	        $datum = date("d.m.Y", $e['eintragTime']);
	        eval("\$entriesHTML .= \"" . DB::getTPL()->get("kondolenzbuch/entry") . "\";");
	    }
	    
	    	    
	 
	    $name = DB::getSettings()->getValue('kondolenz-name');
	    $einleitung = DB::getSettings()->getValue('kondolenz-einleitung');
	  
	    eval("DB::getTPL()->out(\"" . DB::getTPL()->get("kondolenzbuch/index") . "\");");
	    
	    
	}
	
	public static function displayAdministration($selfURL) {
	    $deleted = false;
	    
		if($_REQUEST['action'] == 'deleteAllEntries') {
		    DB::getDB()->query("DELETE FROM kondolenzbuch");
		    
		    $deleted = true;
		}
		
		$anzahlEntries = DB::getDB()->query_first("SELECT COUNT(*) FROM kondolenzbuch");
		$anzahlEntries = $anzahlEntries[0];
		
		$html = "";
		
		eval("\$html = \"" . DB::getTPL()->get("kondolenzbuch/admin/index") . "\";");
		
		return $html;
	}

	
	public static function hasSettings() {
		return true;
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
		return [
		    [
		        'name' => 'kondolenz-name',
		        'typ' => 'ZEILE',
		        'titel' => 'Kondolenzbuch für ...',
		        'text' => 'Name der / des Verstorbenen',
		    ],
		    [
		        'name' => 'kondolenz-einleitung',
		        'typ' => 'HTML',
		        'titel' => 'Einleitungstext',
		        'text' => '',
		    ]
		    
		];
	}
	
	
	public static function getSiteDisplayName() {
		return 'Kondolenzbuch';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return [];	
	}
	
	public static function siteIsAlwaysActive() {
		return false;
	}
	
	public static function hasAdmin() {
		return true;
	}
	
	public static function getAdminMenuGroup() {
		return 'Kondolenzbuch';
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fa-book';
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fa-book';
	}
	
	public static function getAdminGroup() {
		return 'Webportal_Kondolenzbuch_Admin';
	}
	
	public static function onlyForSchool() {
		return [];
	}
	
}


?>