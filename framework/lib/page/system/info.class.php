<?php

class infoPage extends AbstractPage {
	public function __construct($message="", $redirect = "") {
		parent::__construct(array("Information"));

		eval("echo(\"".DB::getTPL()->get('info')."\");");
	}
	
	public function execute() {
		// This page is never to be executed!!!
		die("Execution error!");
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
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
		return '';
	}
	
	public static function siteIsAlwaysActive() {
		return true;
	}
}

class info extends infoPage {
	public function __construct($message,$redirect) {
		parent::__construct($message, $redirect);
	}
}


?>