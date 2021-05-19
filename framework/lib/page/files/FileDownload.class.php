<?php


class FileDownload extends AbstractPage {
	
	public function __construct() {		
		parent::__construct(array(""));     // Keine Navigation
	}

	public function execute() {
		$fileUplaod = FileUpload::getByID(intval($_REQUEST['uploadID']));
		
		if($fileUplaod != null) {			
			if($_REQUEST['accessCode']!= "" && $fileUplaod->getAccessCode() == $_REQUEST['accessCode']) {
			    
			    if($_REQUEST['showPDFPreview'] > 0) {
			        $fileUplaod->sendPreviewForPDFFirstPage($_REQUEST['showPDFPreview']);
			    }
				
				if($_REQUEST['maxWidth'] > 0) {
					$fileUplaod->sendImageWidthMaxWidth(intval($_REQUEST['maxWidth']));
				}

				else $fileUplaod->sendFile();
				exit(0);
			}
		}
	
		
		new errorPage();
		
		
	}
	public static function displayAdministration($selfURL) {
		
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
		return 'Datei Download';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return [];	
	}
	
	public static function siteIsAlwaysActive() {
		return true;
	}
	

	
}


?>