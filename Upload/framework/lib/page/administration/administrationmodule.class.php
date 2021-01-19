<?php




class administrationmodule extends AbstractPage {

	private $page = NULL;
	

	public function __construct() {
		$this->needLicense = false;

		$module = $_REQUEST['module'];
		
		if(in_array($module, requesthandler::getAllowedActions())) {
		    $this->page = $module;
		}
		else {
		    new errorPage();
		}
		
		
		if($module == null || $module == "") {
		    header("Location: index.php?page=administration");
		    exit(0);
		}
		
		
		parent::__construct(array("Verwaltung"), false, true);

		$this->checkLogin();

	}

	public function execute() {
	    
	    $module = $this->page;
		
		if(!DB::getSession()->isAdmin()) {
			if(!in_array($module::getAdminGroup(), DB::getSession()->getGroupNames())) {
				new errorPage();
			}
		}
		
		////
		
		if($_REQUEST['action'] == 'ajaxCompleUserName') {
			$this->ajaxCompleteUserName();
			exit(0);
		}

        if($_REQUEST['action'] == 'ajaxGetSettingsHistory') {
            $this->ajaxGetSettingsHistory();
            exit(0);
        }
		
		if($_REQUEST['action'] == 'addAdmin' && DB::getSession()->isAdmin()) {
			$newUserID = intval($_REQUEST['userID']);
			DB::getDB()->query("INSERT INTO users_groups (userID, groupName) values('" . $newUserID . "','" . $module::getAdminGroup() . "') ON DUPLICATE KEY UPDATE groupName=groupName");
			header("Location: index.php?page=administrationmodule&module=" . $module);
			exit(0);
		}
		
		
		if($_REQUEST['action'] == 'deleteAdmin' && DB::getSession()->isAdmin()) {
			$newUserID = intval($_REQUEST['userID']);
			DB::getDB()->query("DELETE FROM users_groups WHERE userID='" . $newUserID . "' AND groupName='" . $module::getAdminGroup() . "'");
			header("Location: index.php?page=administrationmodule&module=" . $module);
			exit(0);
		}
		
	
		// Logbuch / Notizen
		
		if($_REQUEST['action'] == 'modulAddNote') {
			DB::getDB()->query("INSERT INTO modul_admin_notes (noteModuleName,noteText,noteUserID,noteTime)
					values(
						'" . $module . "',
						'" . DB::getDB()->escapeString($_POST['noteText']) . "',
						'" . DB::getSession()->getUserID(). "',
						UNIX_TIMESTAMP()
					)");
			header("Location: index.php?page=administrationmodule&module=" . $module);
			exit(0);
		}
		
		if($_REQUEST['action'] == 'deleteModuleNote') {
			DB::getDB()->query("DELETE FROM modul_admin_notes WHERE noteID='" . intval($_GET['noteID']) . "'");
			header("Location: index.php?page=administrationmodule&module=" . $module);
			exit(0);
		}
		
		$noteHTML = "";
		
		$notes = DB::getDB()->query("SELECT * FROM modul_admin_notes JOIN users ON userID=noteUserID WHERE noteModuleName='" . $module . "' ORDER BY noteTime DESC");
		while($n = DB::getDB()->fetch_array($notes)) {
			$noteHTML .= "<div class=\"callout callout-info\"><b>" . $n['userFirstName'] . " " . $n['userLastName'] . " (" . $n['userName'] . ")</b><br /><i>" . functions::makeDateFromTimestamp($n['noteTime']) . "</i><br />" . $n['noteText'] . "<br /><a href=\"index.php?page=administrationmodule&module=$module&action=deleteModuleNote&noteID={$n['noteID']}\" class=\"btn btn-danger btn-xs\"><i class=\"fa fa-trash\"></i></a></div>\r\n";
		}
		
		
		
		// Wer hat Zugriff auf die Seite?
		
		$useraccess = "";
		$adminGroup = $module::getAdminGroup();
		
		$adminGroupObject = usergroup::getGroupByName($adminGroup);
		$adminGroupMembers = $adminGroupObject->getMembers();
		
		$globalAdminGroupObject = usergroup::getGroupByName("Webportal_Administrator");
		$globalAdminGroupMembers = $globalAdminGroupObject->getMembers();
		
		$globalAdminGroupList = "";
		
		for($i = 0; $i < sizeof($globalAdminGroupMembers); $i++) {
			$globalAdminGroupList .= "<tr><td>" . $globalAdminGroupMembers[$i]->getDisplayName() . "<br /><small class='pull-right'>" . $globalAdminGroupMembers[$i]->getUserName() . "</small></td></tr>";
		}
		
		$adminGroupList = "";
		
		for($i = 0; $i < sizeof($adminGroupMembers); $i++) {
			$adminGroupList .= "<tr><td>" . $adminGroupMembers[$i]->getDisplayName() . "<br /><small class='pull-right'>" . $adminGroupMembers[$i]->getUserName() . "</small>";

			if(DB::getSession()->isAdmin()) {
				$adminGroupList .= "<a href=\"index.php?page=administrationmodule&module=" . $module . "&action=deleteAdmin&userID=" . $adminGroupMembers[$i]->getUserID() . "\" class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i></a>";
			}
			$adminGroupList .= "</td></tr>\r\n";
		}
		
		
		$html = $module::displayAdministration('index.php?page=administrationmodule&module=' . $this->page);
		
		
		// Einstellungen
		
		$hasSettings = $module::hasSettings();
		
		if($hasSettings) {
			$settings = $module::getSettingsDescription();
			
			for($s = 0; $s < sizeof($settings); $s++) {
				if($_REQUEST['action'] == "modulesavesettings" && DB::checkDemoAccess()) {

					switch($settings[$s]['typ']) {
					    case 'TRENNER': break;
						case "BOOLEAN":
							if($_POST[$settings[$s]['name']] == 1) {
								$saveValue = 1;
							}
							else $saveValue = 0;
							break;
			
						case "TEXT":
						case 'HTML':
						case 'COLOR':
							$saveValue = $_POST[$settings[$s]['name']];
						break;
			
						case "NUMMER":
							$saveValue = intval($_POST[$settings[$s]['name']]);
							break;
			
						case "ZEILE":
							$saveValue = $_POST[$settings[$s]['name']];
							break;
						
						case 'UHRZEIT':
							$saveValue = $_POST[$settings[$s]['name']];
						break;

                        case 'BILD':
                            if($_REQUEST[$settings[$s]['name'] . "_delete"] > 0) {
                                // Datei löschen
                                $saveValue = 0;
                            }
                            else {
                                // Neue Datei hochladen?
                                $fileUpload = FileUpload::uploadPicture($settings[$s]['name'], $settings[$s]['name']);
                                if ($fileUpload['result']) {
                                    // Bei Erfolg

                                    /** @var FileUpload $uploadObject */
                                    $uploadObject = $fileUpload['uploadobject'];
                                    $saveValue = $uploadObject->getID();
                                } else {
                                    $saveValue = DB::getSettings()->getInteger($settings[$s]['name']);
                                }
                            }
                        break;
						
						case 'SELECT':
							$saveValue = $_POST[$settings[$s]['name']];
							if($settings[$s]['multiple']) {
							    $saveValue = implode("~~~~",$saveValue);
							}
						break;
					}

					DB::getSettings()->setValue($settings[$s]['name'], $saveValue);
				}
				else {
					switch($settings[$s]['typ']) {
						case "BOOLEAN":
							if(DB::getSettings()->getValue($settings[$s]['name']) == 1) {
								$selectedTrue = " checked=\"checked\"";
							}
							else {
								$selectedTrue = "";
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
							
						case "COLOR":
						    $value = DB::getSettings()->getValue($settings[$s]['name']);
						    eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/color") . "\";");
						break;
			
						case "HTML":
							$value = DB::getSettings()->getValue($settings[$s]['name']);
							eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/html") . "\";");
							break;

                        case 'BILD':
                            eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/bild") . "\";");
                            break;

                        case 'SELECT':
							$value = DB::getSettings()->getValue($settings[$s]['name']);
							
							$value = explode("~~~~",$value);
						    
						    $mutliple = $settings[$s]['multiple'] ? "multiple" : "";
						    
						    if($settings[$s]['multiple']) $mutlipleAddToName = "[]";
						        else $mutlipleAddToName = "";
						    							
							$selectOptions = '';
							$options = $settings[$s]['options'];
							
							for($i = 0; $i < sizeof($options); $i++) {
								$selectOptions .= '<option value="' . $options[$i]['value'] . '"' . ((in_array($options[$i]['value'], $value)) ? (' selected="selected"') : ('')) . '>' . $options[$i]['name'] . '</option>';
							}
							
							eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/select") . "\";");
						break;
						
						case 'TRENNER':
						    eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/trenner") . "\";");
						break;
							
						case 'UHRZEIT':
							$value = DB::getSettings()->getValue($settings[$s]['name']);

							$minuten = explode(":",$value)[1];
							$stunden = explode(":",$value)[0];

							if($minuten < 10) $minuten = "0" . $minuten;
                            if($stunden < 10) $stunden = "0" . $stunden;


                            eval("\$settingsHTML .= \"" . DB::getTPL()->get("administration/settings/uhrzeit") . "\";");
							break;
					}
				}
			}
		}
		
		if($_REQUEST['action'] == 'modulesavesettings') {
			header("Location: index.php?page=administrationmodule&module=" . $module . "&settings=saved");
			exit(0);
		}
		
		
		
		eval("DB::getTPL()->out(\"" . DB::getTPL()->get("administration/module/index") . "\");");
		PAGE::kill(true);
		//exit(0);
		
	}

	private function ajaxGetSettingsHistory() {
        header("Content-type: application/json");

        $module = $this->page;

        $settingName = $_REQUEST['settingName'];

        $result = [
            'settingName' => $settingName,
            'settingHistoryHTML' => "<table class='table table-striped table-hover table-bordered'><tr><th>Zeitpunkt</th><th>Alter Wert</th><th>Neuer Wert</th><th>Benutzer</th></tr>"
        ];

        $hasSettings = $module::hasSettings();

        /**
         *                 'changeTime' => $d['settingHistoryChangeTime'],
        'oldValue' => $d['settingHistoryOldValue'],
        'newValue' => $d['settingHistoryNewValue'],
        'userID' => $d['settingHistoryUserID']
         */


        if($hasSettings) {
            $settings = $module::getSettingsDescription();

            for($i = 0; $i < sizeof($settings); $i++) {
                if($settings[$i]['name'] == $_REQUEST['settingName']) {
                    $historySettings = DB::getSettings()->getHistory($settings[$i]['name']);



                    for($h = 0; $h < sizeof($historySettings); $h++) {


                        $username = "n/a";

                        if($historySettings[$h]['userID'] == 0) $username = "<i>Systemänderung</i>";

                        $user = user::getUserByID($historySettings[$h]['userID']);
                        if($user != null) $username = $user->getDisplayNameWithFunction();
                        else $username = "n/a";

                        $result['settingHistoryHTML'] .= "<tr>";
                        $result['settingHistoryHTML'] .= "<td>" . functions::makeDateFromTimestamp($historySettings[$h]['changeTime']) . "</td>";

                        if($settings[$i]['typ'] == 'BILD') {

                            $uploadAlt = FileUpload::getByID($historySettings[$h]['oldValue']);
                            $uploadNeu = FileUpload::getByID($historySettings[$h]['newValue']);

                            if($uploadAlt != null) {
                                $result['settingHistoryHTML'] .= "<td><a href='" . $uploadAlt->getURLToFile() . "' target='_blank'>Download / Anzeigen</a></td>";
                            }
                            else {
                                $result['settingHistoryHTML'] .= "<td>Nicht mehr verfügbar</td>";
                            }

                            if($uploadNeu != null) {
                                $result['settingHistoryHTML'] .= "<td><a href='" . $uploadNeu->getURLToFile() . "' target='_blank'>Download / Anzeigen</a></td>";
                            }
                            else {
                                $result['settingHistoryHTML'] .= "<td>Nicht mehr verfügbar</td>";
                            }
                        }
                        else if($settings[$i]['typ'] == 'BOOLEAN') {
                            $result['settingHistoryHTML'] .= "<td>" . ($historySettings[$h]['oldValue'] > 0 ? "Ja" : "Nein") . "</td>";
                            $result['settingHistoryHTML'] .= "<td>" . ($historySettings[$h]['newValue'] > 0 ? "Ja" : "Nein") . "</td>";

                        }
                        else {
                            $result['settingHistoryHTML'] .= "<td>" . $historySettings[$h]['oldValue'] . "</td>";
                            $result['settingHistoryHTML'] .= "<td>" . $historySettings[$h]['newValue'] . "</td>";
                        }


                        $result['settingHistoryHTML'] .= "<td>" . $username . "</td>";

                        $result['settingHistoryHTML'] .= "</tr>";

                    }

                }
            }


        }

        $result['settingHistoryHTML'] .= "</table>";

        echo json_encode($result);
        exit(0);
	}
	
	private function ajaxCompleteUserName() {
		$term = DB::getDB()->escapeString($_REQUEST['term']);
		header("Content-type: text/plain");
		
		
		echo("[\r\n");
		
		
		if(strlen($term) > 2) {
			$users = DB::getDB()->query("SELECT userID, userName, userFirstName, userLastName FROM users WHERE userName LIKE '%" . $term . "%' OR userFirstName LIKE '%" . $term . "%' OR userLastName LIKE '%" . $term . "%'");

			$first = true;
			while($user = DB::getDB()->fetch_array($users)) {
				if(!$first) echo(",");
				if($first) {
					$first = false;
				}
				echo("{\"id\": \"" . $user['userID'] . "\",\r\n");
				echo("\"value\": \"" . $user['userID'] . "\",\r\n");
				echo("\"label\": \"" . addslashes($user['userName'] . " (" . $user['userFirstName'] . " " . $user['userLastName']) . ")\"}\r\n");
			}
		}
		
		
		echo("]\r\n");
	}

	/**
	 * Zeigt einen Kasten an, mit dem Benutzer zur Gruppe $userGroup hinzugefügt oder entfernt werden können.
	 * 
	 * @param unknown $selfURL URL zur Administration
	 * @param unknown $name interner Name des Blocks (ohne Leerzeichen)
	 * @param unknown $actionAdd Aktion zum Hinzufügen
	 * @param unknown $actionDelete Aktion zum Löschen
	 * @param unknown $title Titel des Blocks
	 * @param unknown $beschreibung Beschreibung des Blocks
	 * @param unknown $userGroup Benutzergruppe
	 * @return string
	 */
	public static function getUserListWithAddFunction($selfURL, $name, $actionAdd, $actionDelete, $title, $beschreibung, $userGroup, $multiSelect = false) {
		$html = "";
		
		$userGroup = usergroup::getGroupByName($userGroup);
		
		for($i = 0; $i < sizeof($userGroup->getMembers()); $i++) {
			$displayName = $userGroup->getMembers()[$i]->getDisplayNameWithFunction();
		
			$userList .= "<tr><td>" . $displayName . "</td><td style=\"width:10%\"><a href=\"$selfURL&action=$actionDelete&userID=" . $userGroup->getMembers()[$i]->getUserID() . "\" class=\"btn btn-xs btn-danger\"><i class=\"fa fa-trash\"></i></a></td></tr>";
		
		}
		
		if($userList == '') $userList = "<tr><td><i>Keine Benutzer</i></td></tr>";
		
		if ($multiSelect) {
			eval("\$html .= \"" . DB::getTPL()->get("administration/module/personslistmulti") . "\";");
		} else {
			eval("\$html .= \"" . DB::getTPL()->get("administration/module/personslist") . "\";");

		}
		
		return $html;
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
	
	public static function hasAdmin() {
		return false;
	}
	
	public static function need2Factor() {
	    return TwoFactor::force2FAForAdmin();
	}

}


?>