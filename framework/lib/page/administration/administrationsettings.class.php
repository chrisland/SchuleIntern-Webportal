<?php


/**
 * @deprecated --> Machen alle Module am besten selber
 * @author Christian
 *
 */
class administrationsettings extends AbstractPage {

	private $info;

	public function __construct() {
		$this->needLicense = false;

		parent::__construct(array("Administration", "Einstellungen der einzelnen Module"), false, true);

		$this->checkLogin();

		if(!DB::getSession()->isAdmin()) {
			// Nur für Admins
			header("Location: index.php");
			exit(0);
		}

	}

	public function execute() {

		switch($_GET['action']) {
			default:
				$this->index();
			break;
		}
	}

	private function index() {

		// Statistik

		$pages = requesthandler::getAllowedActions();


		$firstPage = true;

		for($i = 0; $i < sizeof($pages); $i++)  {

			$site = $pages[$i];
			if($site != "error") {
				$classOK = false;

				if(class_exists($site)) {
					// OK :-)
					$classOK = !false;
				}
				else if(class_exists($site . "Page")) {
					$site = $site . "Page";
					$classOK = !false;
				}

				if($classOK) {
					$sectionName = $site::getSiteDisplayName();

					if($site::hasSettings()) {

						$settings = $site::getSettingsDescription();
						$settingsHTML = "";

						$sectionsSelections .= "<li" . ($firstPage ? " class=\"active\"" : "") . "><a href=\"#" . md5($sectionName) . "\"
								data-toggle=\"tab\"><i class=\"fa fas fa-pencil-alt\"></i> $sectionName </a></li>";




						for($s = 0; $s < sizeof($settings); $s++) {
							if($_REQUEST['mode'] == "save") {
								switch($settings[$s]['typ']) {
									case "BOOLEAN":
										if($_POST[$settings[$s]['name']] == 1) {
											$saveValue = 1;
										}
										else $saveValue = 0;
									break;

									case "TEXT":
									case 'HTML':
										$saveValue = $_POST[$settings[$s]['name']];
									break;

									case "NUMMER":
										$saveValue = intval($_POST[$settings[$s]['name']]);
									break;

									case "ZEILE":
										$saveValue = $_POST[$settings[$s]['name']];
									break;
								}

								DB::getDB()->query("INSERT INTO settings (settingName, settingValue) values('" . $settings[$s]['name'] . "','" . DB::getDB()->escapeString(($saveValue)) . "') ON DUPLICATE KEY UPDATE settingValue='" . DB::getDB()->escapeString($saveValue) . "'");
							}
							else {
								switch($settings[$s]['typ']) {
									case "BOOLEAN":
										if(DB::getSettings()->getValue($settings[$s]['name']) == 1) {
											$selectedTrue = " selected=\"selected\"";
											$selectedFalse = "";
										}
										else {
											$selectedTrue = "";
											$selectedFalse = " selected=\"selected\"";
										}
										eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/boolean") . "\";");
									break;

									case "TEXT":
										$value = DB::getSettings()->getValue($settings[$s]['name']);
										eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/text") . "\";");
									break;

									case "NUMMER":
										$value = DB::getSettings()->getValue($settings[$s]['name']);
										eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/nummer") . "\";");
									break;

									case "ZEILE":
										$value = DB::getSettings()->getValue($settings[$s]['name']);
										eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/zeile") . "\";");
									break;

									case "HTML":
									    $value = DB::getSettings()->getValue($settings[$s]['name']);
									    eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/html") . "\";");
									break;
									
									case "SELECT":
									    $value = explode("~~~~",DB::getSettings()->getValue($settings[$s]['name']));
									    
									    
									    $selectOptions = "";
									  
									    $mutliple = $settings[$s]['multiple'] ? "mutliple" : "";
									    
									    Debugger::debugObject($settings[$s]['options'],1);
									    
									    
									    for($o = 0; $o < sizeof($settings[$s]['options']); $o++) {
									        $selected = ((in_array($settings[$s]['options'][$o]['key'], $value)) ? (" selected") : "");
									        
									        $selectOptions .= "<option value=\"" . $settings[$s]['options'][$o]['key'] . "\"$selected>" . $settings[$s]['options'][$o]['value'] . "</option>";
									        
									    }
									    
									    eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/select") . "\";");
								    break;
								}
							}
						}

						if($firstPage) $firstPane = " active";
						else $firstPane = "";

						if($firstPage) $firstPage = false;

						eval("\$sections .= \"" . DB::getTPL()->get("administration/settings/section") . "\";");
					}
				}



			}
		}

		if($_GET['mode'] == "save") {
			eval("echo(\"" . DB::getTPL()->get("administration/settings/saveok") . "\");");
		}
		else {
			eval("echo(\"" . DB::getTPL()->get("administration/settings/index") . "\");");
		}
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
		return 'Administration Einstellungen der Einstellungen';
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