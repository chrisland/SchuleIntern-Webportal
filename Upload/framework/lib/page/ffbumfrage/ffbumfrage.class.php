<?php


class ffbumfrage extends AbstractPage {
	
	public function __construct() {
		
		$this->needLicense = true;
		
		parent::__construct(array("Umfrage"));
		
		$this->checkLogin();
	}

	public function execute() {

	    $umfrageFile = null;
	    $code = null;
	    
	    $isSchueler = false;
	    $isEltern = false;
	    
	    if(DB::getSession()->isEltern()) $isEltern = true;
	    
	    if(DB::getSession()->isPupil()) $isSchueler = true;
	        
	    if($isEltern || $isSchueler) {
	        $codeData = DB::getDB()->query_first("SELECT * FROM ffbumfrage WHERE codeUserID='" . DB::getSession()->getUser()->getUserID() . "'");
	        if($codeData['codeID'] > 0) {
	            $code = $codeData['codeText'];
	        }
	        else {
	            $type = 'SCHUELER';
	            
	            if($isEltern) $type = 'ELTERN';
	            
	            
	            
	            DB::getDB()->query("UPDATE ffbumfrage SET codeUserID='" . DB::getSession()->getUser()->getUserID() . "' WHERE codeType='$type' AND codeUserID=0 LIMIT 1");
	            
	            $codeData = DB::getDB()->query_first("SELECT * FROM ffbumfrage WHERE codeUserID='" . DB::getSession()->getUser()->getUserID() . "'");
	            if($codeData['codeID'] > 0) {
	                header("Location: index.php?page=ffbumfrage");
	                exit(0);
	            }
	            else {
	                new errorPage("Keine Teilname Tans mehr frei.");
	                
	            }           

	        }
	    }
	    else {
	        new errorPage();
	    }
	    
	    if($isSchueler) {
	        $titel = "Schülerumfrage";
	        $umfrageFile = 'https://www.fvm-intern.de/umfragen/schuelerbefragung.htm';
	    }
	    else {
	        $titel = "Elternumfrage";
	        
	        $umfrageFile = 'https://www.fvm-intern.de/umfragen/elternbefragung.htm';   
	    }
	    
	    eval("DB::getTPL()->out(\"" . DB::getTPL()->get('ffbumfrage/index') . "\");");
	    PAGE::kill(true);
			//exit(0);
	    
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
		return 'FFB Umfrage';
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
		return false;
	}
	
	public static function getAdminMenuGroup() {
		return 'Kleinere Module';
	}
	
	public static function getAdminGroup() {
		return 'Webportal FFB Umfrage';
	}


    /**
     * Nur für die Realschule FFB
     * @return String[]
     */
	public static function onlyForSchool() {
	    return [
	        '0468'
	    ];
	}
	
}


?>