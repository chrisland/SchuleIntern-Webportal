<?php


class errorPage extends AbstractPage {
	public function __construct($errorMessage="") {
		parent::__construct(array("Fehler"));
		
		if($errorMessage == "") {
		    $errorMessage = "<i>Unbekannter Fehler.</i>";
		}

		eval("DB::getTPL()->out(\"".DB::getTPL()->get('error', true)."\");");
		
		PAGE::kill(true);
		exit(0);
	}
	
	public function execute() {
		// This page is never to be executed!!!
		die("Execution error!");
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
		return 'Fehlerseite';
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
	
	public static function onlyForSchool() {
		return array();
	}
}

?>