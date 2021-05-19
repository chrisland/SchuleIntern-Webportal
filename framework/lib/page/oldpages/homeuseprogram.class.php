<?php

class homeuseprogram extends AbstractPage {

	private $info;
	
	public function __construct() {
		parent::__construct(array("Lizenzen", "Home-Use-Program (Office für Lehrer)"));
		
		$this->checkLogin();
		
		if(!DB::getSession()->isTeacher()) {
			header("Location: index.php");
			exit(0);
		}
		
	}

	public function execute() {
		$username = DB::getSession()->getData("userName");

		$maildomain = DB::getSettings()->getValue("hup-domain");
        $hupCode = DB::getSettings()->getValue("hup-code");

		eval("echo(\"" . DB::getTPL()->get("homeuseprogram/index") . "\");");
	}
	
	
	public static function getNotifyItems() {
		return array();
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
		        'name' => 'hup-domain',
                'typ' => 'ZEILE',
                'titel' => 'Domain zum HomeUserProgram',
                'text' => ''
            ],
            [
                'name' => 'hup-code',
                'typ' => 'ZEILE',
                'titel' => 'Code zum HomeUserProgram',
                'text' => ''
            ]
        ];
	}
	
	
	public static function getSiteDisplayName() {
		return 'Office HomeUse Program';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	
	}
	
	public static function onlyForSchool() {
		return [];
	}

	public static function getAdminMenuGroup()
    {
        return "Kleinere Module";
    }

    public static function hasAdmin()
    {
        return true;
    }

    public static function getAdminMenuIcon()
    {
        return "fa fa-file-word";
    }
}


?>