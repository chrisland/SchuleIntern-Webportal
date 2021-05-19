<?php



class Lerntutoren extends AbstractPage {

	private $isAdmin = false;
	
	public function __construct() {
		parent::__construct(array("Lerntutoren"));

		$this->checkLogin();

	}

	public function execute() {
	    $this->isAdmin = DB::getSession()->isMember('Webportal_Lerntutoren_Admin');

	    if(DB::getSession()->isAdmin()) $this->isAdmin = true;

		$mode = $_REQUEST['mode'];

		switch($mode) {
			default:
				$this->showIndex();
			break;

            case 'tutoransicht':
                $this->tutorAnsicht();
                break;

            case 'tutoransicht-schueler-json':
                $this->tutorAnsichtSchuelerJson();
                break;

            case 'tutoransicht-add-schueler-to-slot':
                $this->tutorAnsichtAddSchuelerToSlot();
                break;

            case 'tutoransicht-delete-schueler-from-slot':
                $this->tutorAnsichtDeleteSchuelerFromSlot();
                break;
		}

	}

	private function tutorAnsichtAddSchuelerToSlot() {
        $lerntutor = Lerntutor::getBySchueler(DB::getSession()->getPupilObject());

        if($lerntutor == null) new errorPage("Benutzer ist kein Lerntutor");

        $meineSlots = LerntutorSlot::getAllForLerntutor($lerntutor);

        $schueler = schueler::getByAsvID($_REQUEST['schuelerAsvID']);

        for($i = 0; $i < sizeof($meineSlots); $i++) {
            if($meineSlots[$i]->getID() ==  $_POST['slotID']) {

                // Debugger::debugObject($schueler,1);
                $meineSlots[$i]->assignSchueler($schueler);
            }
        }

       //  Debugger::debugObject($_REQUEST,1);

        header("Location: index.php?page=Lerntutoren&mode=tutoransicht");
        exit(0);
    }

    private function tutorAnsichtDeleteSchuelerFromSlot() {
        $lerntutor = Lerntutor::getBySchueler(DB::getSession()->getPupilObject());

        if($lerntutor == null) new errorPage("Benutzer ist kein Lerntutor");

        $meineSlots = LerntutorSlot::getAllForLerntutor($lerntutor);

        $schueler = schueler::getByAsvID($_REQUEST['schuelerAsvID']);

        for($i = 0; $i < sizeof($meineSlots); $i++) {
            if($meineSlots[$i]->getID() ==  $_REQUEST['slotID']) {
                $meineSlots[$i]->assignSchueler(null);
            }
        }

        header("Location: index.php?page=Lerntutoren&mode=tutoransicht");
        exit(0);
    }

	private function tutorAnsichtSchuelerJson() {
        $term = DB::getDB()->escapeString($_REQUEST['term']);
        header("Content-type: text/plain");


        echo("[\r\n");


        if(strlen($term) >= 2) {
            $users = DB::getDB()->query("SELECT schuelerAsvID, schuelerName, schuelerRufname, schuelerVornamen, schuelerKlasse FROM schueler WHERE 

                schuelerName LIKE '%" . $term . "%' OR schuelerRufname LIKE '%" . $term . "%' OR schuelerVornamen LIKE '%" . $term . "%'");

            $first = true;

            while($user = DB::getDB()->fetch_array($users)) {
                if(!$first) echo(",");
                if($first) {
                    $first = false;
                }
                echo("{\"id\": \"" . $user['schuelerAsvID'] . "\",\r\n");
                echo("\"value\": \"" . $user['schuelerAsvID'] . "\",\r\n");
                echo("\"label\": \"" . addslashes($user['schuelerKlasse'] . ": " . $user['schuelerName'] . ", " . $user['schuelerRufname']) . "\"}\r\n");
            }
        }


        echo("]\r\n");
        exit(0);
    }

	private function tutorAnsicht() {


	    $lerntutor = Lerntutor::getBySchueler(DB::getSession()->getPupilObject());

	    if($lerntutor == null) new errorPage();

	    $meineSlots = LerntutorSlot::getAllForLerntutor($lerntutor);


        $slotsHTML = "";

	    for($i = 0; $i < sizeof($meineSlots); $i++) {
            $slotsHTML .= "<tr><td>" . $meineSlots[$i]->getFach() . "</td>";
            $slotsHTML .= "<td>" . $meineSlots[$i]->getJGS() . "</td>";
            $slotsHTML .= "<td>";

            /** @var schueler $schueler */
            $schueler = $meineSlots[$i]->getSchuelerBelegt();

            if($schueler === null) {
                $slotsHTML .= "Frei<br />";

                $slotsHTML .= "<button class=\"btn btn-primaty btn-sm\" onclick=\"assignSchueler(" . $meineSlots[$i]->getID() . ")\"><i class=\"fa fa-plus\"></i> Schueler zuweisen</button>";

            }
            else {
                $slotsHTML .= $schueler->getCompleteSchuelerName() . " (Klasse " . $schueler->getKlasse() . ")";
                $slotsHTML .= " <button type=\"button\" class='btn btn-danger btn-xs' onclick=\"confirmAction('Zuordnung löschen?','index.php?page=Lerntutoren&mode=tutoransicht-delete-schueler-from-slot&slotID=" . $meineSlots[$i]->getID() . "')\"><i class=\"fa fa-trash\"></i></button>";
            }

            $slotsHTML .= "</td>";
            $slotsHTML .= "</tr>";
        }

        eval("echo(\"" . DB::getTPL()->get("lerntutoren/tutoransicht/index") . "\");");


    }

	private function showIndex() {
	    $admin = usergroup::getGroupByName(self::getAdminGroup());

	    $users = $admin->getMembers();

	    $user = null;
	    $addCC = "";
	    if(sizeof($users) > 0) {
	        $user = $users[0];
	        $addCC = "&ccrecipient=U:" . $user->getUserID();
        }


	    $tutoren = Lerntutor::getAll();

	    $slotsHTML = "";

	    $istSelbstTutor = false;



	    for($i = 0; $i < sizeof($tutoren); $i++) {

	        if(DB::getSession()->isPupil() && $tutoren[$i]->getSchueler()->getAsvID() == DB::getSession()->getPupilObject()->getAsvID()) $istSelbstTutor = true;

	        $slots = $tutoren[$i]->getSlots();
	        for($s = 0; $s < sizeof($slots); $s++) {
                $slotsHTML .= '
                <tr>
                    <td>
                        ' . $tutoren[$i]->getSchueler()->getCompleteSchuelerName() . ' (Klasse ' . $tutoren[$i]->getSchueler()->getKlasse() . ')
                    </td>
                    <td>
                       ' . $slots[$s]->getFach() . '
                    </td>
                    <td>
                       ' . $slots[$s]->getJGS() . '
                    </td>
                    <td>
                        <a href="index.php?page=MessageCompose&recipient=U:' . $tutoren[$i]->getSchueler()->getUser()->getUserID() . $addCC . '" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kontaktieren</a>
                    </td>
                 </tr>
            ';
            }

        }

		eval("echo(\"" . DB::getTPL()->get("lerntutoren/schuelereltern/index") . "\");");
	}

    private function contact() {
        eval("echo(\"" . DB::getTPL()->get("lerntutoren/schuelereltern/contact") . "\");");
    }

	public static function getNotifyItems() {
		return array();
	}

	public static function hasSettings() {
		return true;
	}
	
	public static function displayAdministration($selfURL) {

	    switch($_REQUEST['action']) {
            default:
                return self::adminIndex($selfURL);
                break;

            case 'addLerntutor':
                $schueler = schueler::getByAsvID($_REQUEST['schuelerAsvID']);
                if($schueler != null) {
                    Lerntutor::addLerntutor($schueler);
                }

                header("Location: $selfURL");
                exit(0);

            case 'ajaxSearchSchueler':
                self::adminAjaxSearchSchueler($selfURL);
                break;

            case 'addSlot':
                self::adminAddSlot($selfURL);
                break;

            case 'deleteSlot':
                self::deleteSlot($selfURL);
                break;


            case 'deleteLerntutor':
                self::adminDeleteLerntutor($selfURL);
                break;

        }


	}

    /**
     * @param $selfURL
     */
	private static function adminAddSlot($selfURL) {
        $lerntutor = Lerntutor::getByID($_REQUEST['lerntutorID']);
        if($lerntutor != null) {
            LerntutorSlot::addSlotToLerntutor($lerntutor, $_REQUEST['fach'], $_REQUEST['jahrgangsstufe']);
        }

        header("Location: $selfURL");
        exit(0);
    }

	private static function adminDeleteLerntutor($selfURL) {
        $lerntutor = Lerntutor::getByID($_REQUEST['lerntutorID']);
        if($lerntutor != null) $lerntutor->delete();

        header("Location: $selfURL");
        exit(0);
    }

    private static function deleteSlot($selfURL) {
	    $slot = LerntutorSlot::getByID($_REQUEST['slotID']);
	    if($slot != null) {
	        $slot->delete();
        }

	    header("Location: $selfURL");
	    exit(0);
    }

	private static function adminIndex($selfURL) {

        $lerntutorenHTML = "";

        $lerntutoren = Lerntutor::getAll();
        for($i = 0; $i < sizeof($lerntutoren); $i++) {
            $lerntutorenHTML .= "
                <tr>
                    <td>" . $lerntutoren[$i]->getSchueler()->getCompleteSchuelerName() . "</td>
            ";

            $slots = $lerntutoren[$i]->getSlots();

           // Debugger::debugObject($slots);

            $lerntutorenHTML .= "<td>";

            $lerntutorenHTML .= "<ul>";

            for($s = 0; $s < sizeof($slots); $s++) {
                // Debugger::debugObject($slots[$s]);
                $lerntutorenHTML .= "<li>" . $slots[$s]->getFach() . " für Jahrgangsstufe " . $slots[$s]->getJGS();

                if($slots[$s]->getSchuelerBelegt() != null) {
                    $lerntutorenHTML .= "<label class=\"label label-info\">Belegt</label> (" . $slots[$i]->getSchuelerBelegt()->getCompleteSchuelerName() . ")";
                }
                else {
                    $lerntutorenHTML .= " <label class=\"label label-success\">Frei</label>";
                }

                $lerntutorenHTML .= "<br /><button type='button' onclick=\"confirmAction('Soll der Slot wirklich gelöscht werden?','" . $selfURL . "&action=deleteSlot&slotID=" . $slots[$s]->getID() . "')\" class='btn btn-danger btn-xs'><i class=\"fa fa-trash\"></i> Slot löschen</a>";

                $lerntutorenHTML .= "</li>";
            }

            $lerntutorenHTML .= "</ul>";


            if(sizeof($slots) == 0) {
                $lerntutorenHTML .= "<i>Keine Slots angelegt</i>";
            }

            $lerntutorenHTML .= "<hr noshade>";
            $lerntutorenHTML .= '
                <form action="' . $selfURL . '&action=addSlot&lerntutorID=' . $lerntutoren[$i]->getID() . '" method="post">
                    <div class="input-group">
                        <label>Fach</label>
                        <input type="text" class="form-control" name="fach">  
                    </div>
                    <div class="input-group">
                        <label>Jahrgangsstufe</label>
                        <input type="text" class="form-control" name="jahrgangsstufe">  
                    </div>
                    <p>
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Slot anlegen</button>
                        </p>
                
                </form>
            ';


            $lerntutorenHTML .= "</td>";

                $lerntutorenHTML .= "<td>";
                $lerntutorenHTML .= "<button type=\"button\" class=\"btn btn-danger btn-sm\"
                        onclick=\"confirmAction('Soll der Lerntutor mit Slots wirklich gelöscht werden?',
                            '$selfURL&action=deleteLerntutor&lerntutorID={$lerntutoren[$i]->getID()}')\"><i class=\"fa fa-trash\"></i></button>";

                $lerntutorenHTML .= "</td>";



                $lerntutorenHTML .= "</tr>";





        }



        $html = "";
        eval("\$html = \"" . DB::getTPL()->get("lerntutoren/admin/index") . "\";");

        return $html;
    }

	private static function adminAjaxSearchSchueler($selfURL) {
        $term = DB::getDB()->escapeString($_REQUEST['term']);
        header("Content-type: text/plain");


        echo("[\r\n");


        if(strlen($term) >= 2) {
            $users = DB::getDB()->query("SELECT schuelerAsvID, schuelerName, schuelerRufname, schuelerVornamen, schuelerKlasse FROM schueler WHERE 

                schuelerName LIKE '%" . $term . "%' OR schuelerRufname LIKE '%" . $term . "%' OR schuelerVornamen LIKE '%" . $term . "%'");

            $first = true;

            while($user = DB::getDB()->fetch_array($users)) {
                if(!$first) echo(",");
                if($first) {
                    $first = false;
                }
                echo("{\"id\": \"" . $user['schuelerAsvID'] . "\",\r\n");
                echo("\"value\": \"" . $user['schuelerAsvID'] . "\",\r\n");
                echo("\"label\": \"" . addslashes($user['schuelerKlasse'] . ": " . $user['schuelerName'] . ", " . $user['schuelerRufname']) . "\"}\r\n");
            }
        }


        echo("]\r\n");
        exit(0);
    }

	public static function getSettingsDescription() {
		return [
		    [
		        'name' => "lerntutoren-disclaimer",
		        'typ' => 'HTML',
		        'titel' => "Generelle Hinweise für die Lerntutoren",
		        'text' => ""
		    ]
        ];
	}


	public static function getSiteDisplayName() {
		return 'Lerntutoren';
	}

	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();

	}

	public static function hasAdmin() {
		return true;
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fa-graduation-cap';
	}
	
	public static function getAdminGroup() {
		return 'Webportal_Lerntutoren_Admin';
	}
	
	public static function getAdminMenuGroup() {
		return 'Lerntutoren';
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fa-graduation-cap';
	}
}


?>