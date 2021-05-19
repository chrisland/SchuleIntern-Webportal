<?php




class downloadsteacher extends AbstractPage {

	private $info;

	protected $needLicense = false;
	
	public function __construct() {
		parent::__construct(array("Downloads", "Lehrersoftware"));
		
		$this->checkLogin();
		
		if(!DB::getSession()->isTeacher()) {
			header("Location: index.php");
			exit(0);
		}
		
	}

	public function execute() {
		eval("DB::getTPL()->out(\"" . DB::getTPL()->get("downloads/teacher") . "\");");
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
		return 'Downloads für Lehrer';
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