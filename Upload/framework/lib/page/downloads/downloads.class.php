<?php

class downloads extends AbstractPage {

	private $info;
	
	public function __construct() {
		parent::__construct(array("Downloads", "Unterrichtssoftware"));
		
		$this->checkLogin();
		
		if(!(DB::getSession()->isTeacher() || DB::getSession()->isPupil())) {
			new errorPage("Dieser Bereich ist nur für Schüler und Lehrer.");
			exit(0);
		}
		
	}

	public function execute() {
		eval("echo(\"" . DB::getTPL()->get("downloads/index") . "\");");
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
		return 'Downloads für Schüler';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	
	}
	
	public static function onlyForSchool() {
		return array(
		);
	}
}


?>