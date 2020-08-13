<?php


/**
 * Diese Klasse dient der Eingabe der Beobachtungen zu allen Schülern.
 * @author Christian
 */
class beobachtungsbogen extends AbstractPage {
	
	private $bogen = NULL;
	private $bogenID = NULL;
	private $fach = NULL;
	private $klasse = NULL;
	private $myUserID = NULL;
	private $myRealName = NULL;
	private $schueler = array();
	private $fragen = array();
	private $bogenIsActive = false;
	private $nachfristBox = "";
	
	public function __construct() {
		
		$this->needLicense = true;
		
		parent::__construct(array("Beobachtungsbogen", "Beobachtungsbogen - Eintragen für Klassenlehrer"));
		
		
		$this->checkLogin();
		
		if(!DB::getSession()->isTeacher()) {
			new error("Dieser Bereich ist nur für Lehrer zur Verfügung!");
			exit(0);
		}
		
	}
	
	public function execute() {
		$mode = $_GET['mode'];
		$bogenID = intval($_GET['bogenID']);
		
		$currentBoegen = $this->getCurrentBoegen();
		
		if(sizeof($currentBoegen) == 0) {
			eval("echo(\"" . DB::getTPL()->get("beobachtungsbogen/eintragen/keinbogen") . "\");");
			PAGE::kill(true);
      //exit(0);
		}
		
		$bogenOK = false;
		if($bogenID > 0) {
			for($i = 0; $i < sizeof($currentBoegen); $i++) {
				if($currentBoegen[$i]['beobachtungsbogenID'] == $bogenID) {
					$this->bogen = $currentBoegen[$i];
					$this->bogenID = $this->bogen['beobachtungsbogenID'];
					$this->bogenIsActive = $this->bogen['isActive'] > 0;
					
					if(!$this->bogenIsActive) {
						// Eventuell Nachfrist?
						$nachfrist = DB::getDB()->query_first("SELECT * FROM beobachtungsbogen_eintragungsfrist WHERE beobachtungsbogenID='" . $this->bogenID . "' AND userID='" . DB::getUserID() . "' AND frist >= curdate()");
						
						
						if($nachfrist['userID'] > 0) {
							$this->bogenIsActive = true;
							$datum = functions::getFormatedDateFromSQLDate($nachfrist['frist']);
							
							eval("\$this->nachfristBox = \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/nachfrist") . "\";");
						}
						else $this->nachfristBox = "";
					}
					
					$bogenOK = true;
				}
			}
		}
		else {
			if(sizeof($currentBoegen) == 1) {
				// nur ein Bogen --> Umleiten
				header("Location: index.php?page=beobachtungsbogen&bogenID=" . $currentBoegen[0]['beobachtungsbogenID']);
				exit(0);
			}
			else {
				// Auswahl der Bögen anzeigen
				for($i = 0; $i < sizeof($currentBoegen); $i++) {
					$bogen = $currentBoegen[$i];
					
					eval("\$bogenHTML .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/multibogen_bit") . "\";");
				}
				
				if($bogenHTML == "") $bogenHTML = "<tr><td colspan=\"5\" style=\"text-align: center\"><strong><i class=\"fa fa-ban\"></i> Keiner vorhanden</strong></td></tr>";
				
				eval("echo(\"" . DB::getTPL()->get("beobachtungsbogen/eintragen/multibogen") . "\");");
				PAGE::kill(true);
      	//exit(0);
			}
		}
		
		if(!$bogenOK) {
			new error("Der angeforderte Beobachtungsbogen ist ungültig!");
			exit(0);
		}
		
		
		
		switch($mode) {
			default:
				$this->showIndex();
			break;
			
			case "markAsDone":
				$this->markOKStatus(true);
			break;
			
			case "markAsUnDone":
				$this->markOKStatus(false);
			break;
			
			case "perPupil":
				$this->perPupil();
			break;
			
			case "perQuestion":
				$this->perQuestion();
			break;
			
			case "allInOne":
				$this->allInOne();
			break;
			
			case "print":
				$this->printit();
			break;
		}	
	}
	
	private function getCurrentBoegen() {
		$boegen = DB::getDB()->query("SELECT *, (beobachtungsbogenDeadline >= CURDATE()) AS isActive FROM beobachtungsbogen_boegen WHERE beobachtungsbogenDatum >= CURDATE() AND beobachtungsbogenStartDate <= CURDATE()");
		$result = array();
		while($b = DB::getDB()->fetch_array($boegen)) {
			$result[] = $b;
		}
		
		return $result;
	}
	
	private function checkGradeAccess() {
		$myName = DB::getSession()->getTeacherObject()->getKuerzel();
		$lehrer = DB::getDB()->query_first("SELECT * FROM beobachtungsbogen_klasse_fach_lehrer WHERE lehrerKuerzel LIKE '" . $myName . "' AND beobachtungsbogenID='" . $this->bogenID . "' AND klasseName='" . addslashes($_REQUEST['grade']) . "' AND fachName='" . addslashes($_REQUEST['subject']) . "'");
		if($lehrer['beobachtungsbogenID'] > 0) {
			$this->fach = $lehrer['fachName'];
			$this->klasse = $lehrer['klasseName'];
			$this->myRealName = $lehrer['lehrerKuerzel'];
			$this->myUserID = DB::getSession()->getUserID();
			
			$this->schueler = array();
			
			$klasse = klasse::getByName($this->klasse);
			if($klasse != null) {
				$schueler = $klasse->getSchueler(false);
				
				for($i = 0; $i < sizeof($schueler); $i++) {
					$this->schueler[] = array(
							"userID" => $schueler[$i]->getSchuelerUserID(),
							"userFirstName" => $schueler[$i]->getRufname(),
							"userLastName" => $schueler[$i]->getName()
					);
				}
			}
			
// 			$this->schueler = pupil::getUsersOfGrade($this->klasse);
			$this->fragen = $this->getFragenData();
		}
		else {
			new error("Zugriffsfehler: Kein Zugriff auf dieses Fach und/oder Klasse!");
			exit(0);
		}
	}
	
	private function printit() {
		$this->checkGradeAccess();
		
		$titel = $this->bogen['beobachtungsbogenTitel'];
		$titel = str_replace("<br />"," - ", $titel);
		$titel = strip_tags($titel);
		
		$smiley1 = "<img src=\"images/beobachtungsbogen/1.jpg\" width=\"10\"><img src=\"images/beobachtungsbogen/1.jpg\" width=\"10\">";
		$smiley2 = "<img src=\"images/beobachtungsbogen/1.jpg\" width=\"10\">";
		$smiley3 = "<img src=\"images/beobachtungsbogen/2.jpg\" width=\"10\">";
		$smiley4 = "<img src=\"images/beobachtungsbogen/3.jpg\" width=\"10\">";
		$smiley5 = "<img src=\"images/beobachtungsbogen/3.jpg\" width=\"10\"><img src=\"images/beobachtungsbogen/3.jpg\" width=\"10\">";
		
		
		$bigMatrix = "<tr><th><b>Schüler / Frage</b></th>";
	
		for($i = 0; $i < sizeof($this->fragen); $i++) {
			if($this->fragen[$i]['frageTyp'] == 2) {
				$only = "<br /><small>nur $smiley1 bis $smiley3</small>";
			}
			else $only = "";
			$bigMatrix .= "<th>" . $this->fragen[$i]['frageText'] . "$only</th>";
		}
	
		$bigMatrix .= "</tr>";
	
		$tabIndex = 1;
	
		for($s = 0; $s < sizeof($this->schueler); $s++) {
			$bigMatrix .= "<tr><td>" . $this->schueler[$s]['userLastName'] . ", " . $this->schueler[$s]['userFirstName'] . "</td>";
				
			$answersPupil = $this->getFragenDataForPupil($this->schueler[$s]['userID']);
				
			$userID = $this->schueler[$s]['userID'];
				
			for($i = 0; $i < sizeof($this->fragen); $i++) {
				$frageID = $this->fragen[$i]['frageID'];
	
	
				if($answersPupil[$frageID] != "") {
					$answersPupil[$frageID] += 3;
				}
				else {
					$answersPupil[$frageID] = "x";
				}
	
				$thisTabIndex = ($tabIndex + $i*sizeof($this->schueler));
	
				if($this->fragen[$i]['frageTyp'] == 1) $check = "checkInputTyp1";
				else $check = "checkInputTyp2";
	
				$startIcon = "";
				switch($answersPupil[$frageID]) {
					case "x": $startIcon = "-"; break;
					case "1": $startIcon = $smiley1; break;
					case "2": $startIcon = $smiley2; break;
					case "3": $startIcon = $smiley3; break;
					case "4": $startIcon = $smiley4; break;
					case "5": $startIcon = $smiley5; break;
				}
	
				eval("\$bigMatrix .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/print/field") . "\";");
			}
				
			$tabIndex++;
				
			$bigMatrix .= "</tr>";
		}
		
		$heute = functions::makeDateFromTimestamp(time());
	
		
		eval("\$printHTML = \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/print/index") . "\";");
			
		$printHTML = ($printHTML);

		$print = new PrintNormalPageA4WithoutHeader("Eigene_Beobachtungen_Klasse_" . $this->klasse . ".pdf");
		$print->setHTMLContent($printHTML);
		$print->send();

		exit(0);
	}
	
	private function allInOne() {
		$this->checkGradeAccess();
		$this->checkIsActive();
		
		if($_GET['save'] > 0) {
						
			for($s = 0; $s < sizeof($this->schueler); $s++) {					
				$userID = $this->schueler[$s]['userID'];
				for($i = 0; $i < sizeof($this->fragen); $i++) {
					$frageID = $this->fragen[$i]['frageID'];
					
					$data = $_POST[$frageID . "_" . $userID];
					
					if($data > 0) {
						if($this->fragen[$i]['frageTyp'] == 1 && $data < 6) {
							$this->saveForPupil($userID, $frageID, $data-3);
						}
						else if($this->fragen[$i]['frageTyp'] == 2 && $data < 4) {
							$this->saveForPupil($userID, $frageID, $data-3);
						}
						else {
							$this->saveForPupil($userID, $frageID, "x");
						}
					}
					else {
						$this->saveForPupil($userID, $frageID, "x");
					}					
				}
			}

			$this->markOKStatus(true);
			exit(0);
		}
		
		$bigMatrix = "<tr><th>Schüler / Frage</th>";
		
		for($i = 0; $i < sizeof($this->fragen); $i++) {
			if($this->fragen[$i]['frageTyp'] == 2) {
				$only = "<br /><small>nur <i class=\"fa fa-smile-o\"></i><i class=\"fa fa-smile-o\"></i>(Nr. 1) bis <i class=\"fa fa-meh-o\"></i>(Nr. 3)</small>";
			}
			else $only = "";
			$bigMatrix .= "<th>" . $this->fragen[$i]['frageText'] . "$only</th>";
		}
		
		$bigMatrix .= "</tr>";
		
		$tabIndex = 1;
		
		for($s = 0; $s < sizeof($this->schueler); $s++) {
			$bigMatrix .= "<tr><td>" . $this->schueler[$s]['userLastName'] . ", " . $this->schueler[$s]['userFirstName'] . "</td>";
			
			$answersPupil = $this->getFragenDataForPupil($this->schueler[$s]['userID']);
			
			$userID = $this->schueler[$s]['userID'];
			
			for($i = 0; $i < sizeof($this->fragen); $i++) {
				$frageID = $this->fragen[$i]['frageID'];
				
				
				if($answersPupil[$frageID] != "") {
					$answersPupil[$frageID] += 3;
				}
				else {
					$answersPupil[$frageID] = "x";
				}
				
				$thisTabIndex = ($tabIndex + $i*sizeof($this->schueler));
				
				if($this->fragen[$i]['frageTyp'] == 1) $check = "checkInputTyp1";
				else $check = "checkInputTyp2";
				
				$startIcon = "";
				switch($answersPupil[$frageID]) {
					case "x": $startIcon = "<i class=\"fa fa-ban\"></i>"; break;
					case "1": $startIcon = "<i class=\"fa fa-smile-o\"></i><i class=\"fa fa-smile-o\"></i>"; break;
					case "2": $startIcon = "<i class=\"fa fa-smile-o\"></i>"; break;
					case "3": $startIcon = "<i class=\"fa fa-meh-o\"></i>"; break;
					case "4": $startIcon = "<i class=\"fa fa-frown-o\"></i>"; break;
					case "5": $startIcon = "<i class=\"fa fa-frown-o\"></i><i class=\"fa fa-frown-o\"></i>"; break;
				}
				
				eval("\$bigMatrix .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/allinone/field") . "\";");
			}
			
			$tabIndex++;
			
			$bigMatrix .= "</tr>";
		}
		
		eval("echo(\"" . DB::getTPL()->get("beobachtungsbogen/eintragen/allinone/index") . "\");");
		PAGE::kill(true);
    //exit(0);
	}
	
	// $bewertung = "X", wenn "keine Auswahl"
	private function saveForPupil($userID, $frageID, $bewertung) {
		if($bewertung === 'x' || $bewertung === 'X') {
			DB::getDB()->query("DELETE FROM beobachtungsbogen_fragen_daten WHERE frageID='" . $frageID . "' AND schuelerID='" . $userID . "' AND fachName='" . $this->fach . "' AND lehrerKuerzel='" . $this->myRealName . "'");
		}
		else {
			DB::getDB()->query("INSERT INTO beobachtungsbogen_fragen_daten (frageID, schuelerID, lehrerKuerzel, fachName, bewertung) values('$frageID','$userID','" . $this->myRealName . "','" . $this->fach . "','$bewertung') ON DUPLICATE KEY UPDATE bewertung='$bewertung'");
		}
	}
	
	private function perQuestion() {
		$this->checkGradeAccess();
		$this->checkIsActive();
	
		$index = $_GET['index'];
		
		if($index == "") $index = 0;
		

		
		if($_POST['save'] != "") {
			$frage = $this->fragen[$index];
			
			for($i = 0; $i < sizeof($this->schueler); $i++) {
				$this->saveForPupil($this->schueler[$i]['userID'], $frage['frageID'], $_POST['bewertung_' . $this->schueler[$i]['userID']]);
			}
			
			switch($_POST['save']) {
				case "savenext":
					$index++;
				break;
				
				case "saveend":
					$this->markOKStatus(true);
					exit(0);
				break;
				
				case "saveprev":
					$index--;
				break;
			}
		}
		
		if($index < 0 || $index >= sizeof($this->fragen)) {
			new error("Ungültiger index ($index in questionArray)");
			exit(0);
		}
		
		$frage = $this->fragen[$index];		
		
		
		$schuelerHTML = "";
		
		for($i = 0; $i < sizeof($this->schueler); $i++) {
			$fragenData = $this->getFragenDataForPupil($this->schueler[$i]['userID']);
			if($frage['frageTyp'] == 1) {
				$selected = array(
						"-2" => "",
						"-1" => "",
						"0" => "",
						"1" => "",
						"2" => "",
						"X" => ""
				);
			
				if(isset($fragenData[$frage['frageID']])) {
					$selected[$fragenData[$frage['frageID']]] = " checked=\"checked\"";
				}
				else {
					$selected['X'] = " checked=\"checked\"";
				}
			
				eval("\$schuelerHTML .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/perfrage/fragetyp1") . "\";");
			}
				
			if($frage['frageTyp'] == 2) {
				$selected = array(
						"-2" => "",
						"-1" => "",
						"0" => "",
						"X" => ""
				);
					
				if(isset($fragenData[$frage['frageID']])) {
					$selected[$fragenData[$frage['frageID']]] = " checked=\"checked\"";
				}
				else {
					$selected['X'] = " checked=\"checked\"";
				}
					
				eval("\$schuelerHTML .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/perfrage/fragetyp2") . "\";");
			}
		}
		
		$nr = $index+1;
		$von = sizeof($this->fragen);
		
		if($index == 0) {
			$prev = "&nbsp;";
			$next = "<button name=\"save\" value=\"savenext\" type=\"submit\" class=\"form form-control\">Speichern und weiter</button>";
		}
		elseif($index == (sizeof($this->fragen)-1)) {
			$prev = "<button name=\"save\" value=\"saveprev\" type=\"submit\" class=\"form form-control\">Speichern und zurück</button>";
			$next = "<button name=\"save\" value=\"saveend\" type=\"submit\" class=\"form form-control\">Speichern und Abschließen</button>";
		}
		else {
			$prev = "<button name=\"save\" value=\"saveprev\" type=\"submit\" class=\"form form-control\">Speichern und zurück</button>";
			$next = "<button name=\"save\" value=\"savenext\" type=\"submit\" class=\"form form-control\">Speichern und weiter</button>";
		}
		
		eval("echo(\"" . DB::getTPL()->get("beobachtungsbogen/eintragen/perfrage/index") . "\");");
		PAGE::kill(true);
    //exit(0);
	
	}
	
	private function perPupil() {
		$this->checkGradeAccess();
		
		$this->checkIsActive();
		
		$index = $_GET['index'];
				
		if($_POST['save'] != "") {
			$method = $_POST['save'];
			
			if($index == "") {
				new error("Ungültiger Aufruf! (index empty!)");
				exit(0);
			}
			
			for($i = 0; $i < sizeof($this->fragen); $i++) {
				$this->saveForPupil($this->schueler[$index]['userID'], $this->fragen[$i]['frageID'], $_POST['bewertung_' . $this->fragen[$i]['frageID']]);
			}
			
			switch($method) {
				case "savenext":
					$index++;
				break;
				
				case "saveend":
					$this->markOKStatus(true);
					exit(0);
				break;
				
				case "saveprev":
					$index--;
				break;
			}		
		}

		if($index == "") $index = 0;
		
		if($index < sizeof($this->schueler)) {
			// OK
		}
		else if($index >= 0) {
			// OK
		}
		else {
			new error("Ungültiger Index angegeben!");
			exit(0);
		}
		
		$schueler = $this->schueler[$index];	

		$alle = sizeof($this->schueler);
		$nr = $index+1;
		
		$fragenData = $this->getFragenDataForPupil($schueler['userID']);
		
		$fragenHTML = "";
		for($i = 0; $i < sizeof($this->fragen); $i++) {
			if($this->fragen[$i]['frageTyp'] == 1) {
				$selected = array(
					"-2" => "",
					"-1" => "",
					"0" => "",
					"1" => "",
					"2" => "",
					"X" => ""
				);
				
				if(isset($fragenData[$this->fragen[$i]['frageID']])) {
					$selected[$fragenData[$this->fragen[$i]['frageID']]] = " checked=\"checked\"";
				}
				else {
					$selected['X'] = " checked=\"checked\"";
				}
				
				eval("\$fragenHTML .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/perschueler/fragetyp1") . "\";");
			}
			
			if($this->fragen[$i]['frageTyp'] == 2) {
				$selected = array(
						"-2" => "",
						"-1" => "",
						"0" => "",
						"X" => ""
				);
			
				if(isset($fragenData[$this->fragen[$i]['frageID']])) {
					$selected[$fragenData[$this->fragen[$i]['frageID']]] = " checked=\"checked\"";
				}
				else {
					$selected['X'] = " checked=\"checked\"";
				}
			
				eval("\$fragenHTML .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/perschueler/fragetyp2") . "\";");
			}
		}
		
		if($index == 0) {
			$prev = "&nbsp;";
			$next = "<button name=\"save\" value=\"savenext\" type=\"submit\" class=\"form form-control\">Speichern und weiter</button>";
		}
		elseif($index == (sizeof($this->schueler)-1)) {
			$prev = "<button name=\"save\" value=\"saveprev\" type=\"submit\" class=\"form form-control\">Speichern und zurück</button>";
			$next = "<button name=\"save\" value=\"saveend\" type=\"submit\" class=\"form form-control\">Speichern und Abschließen</button>";
		}
		else {
			$prev = "<button name=\"save\" value=\"saveprev\" type=\"submit\" class=\"form form-control\">Speichern und zurück</button>";
			$next = "<button name=\"save\" value=\"savenext\" type=\"submit\" class=\"form form-control\">Speichern und weiter</button>";
		}
		
		eval("echo(\"" . DB::getTPL()->get("beobachtungsbogen/eintragen/perschueler/index") . "\");");
		
	}
	
	private function getFragenDataForPupil($userID) {
		$result = array();
		$data = DB::getDB()->query("SELECT * FROM beobachtungsbogen_fragen_daten WHERE frageID IN (SELECT frageID FROM beobachtungsbogen_fragen WHERE beobachtungsbogenID='" . $this->bogenID . "') AND schuelerID='" . $userID . "' AND lehrerKuerzel LIKE '" . $this->myRealName . "' AND fachName LIKE '" . $this->fach . "'");
		while($d = DB::getDB()->fetch_array($data)) {
			$result[$d['frageID']] = $d['bewertung'];
		}
		
		return $result;
	}
	
	private function getFragenData() {
		$klassenLeitungKlasse = DB::getDB()->query("SELECT * FROM beobachtungsbogen_klassenleitung WHERE klassenName='" . $this->klasse . "' AND beobachtungsbogenID='" . $this->bogenID . "'");
		$kls = array();
		while($kl = DB::getDB()->fetch_array($klassenLeitungKlasse)) {
			$kls[] = $kl['klassenleitungUserID'];
		}
		
		$result = array();
		$fragen = DB::getDB()->query("SELECT * FROM beobachtungsbogen_fragen WHERE beobachtungsbogenID='" . $this->bogenID . "'");
		while($f = DB::getDB()->fetch_array($fragen)) {
			if($f['frageZugriff'] == "LEHRER" || ($f['frageZugriff'] == "KLASSENLEITUNG" && in_array(DB::getUserID(),$kls))) $result[] = $f;
		}
		
		return $result;
	}
	
	private function markOKStatus($newStatus) {
		$this->checkGradeAccess();
		
		$this->checkIsActive();
		
		DB::getDB()->query("UPDATE beobachtungsbogen_klasse_fach_lehrer SET isOK=" . ($newStatus ? 1 : 0) . " WHERE
			beobachtungsBogenID='" . $this->bogenID . "' AND
			klasseName='" . $this->klasse . "' AND
			fachName='" . $this->fach . "' AND
			lehrerKuerzel LIKE '" . $this->myRealName . "'");
		
		header("Location: index.php?page=beobachtungsbogen&bogenID=" . $this->bogenID);
		exit(0);
	}
	
	private function checkIsActive() {
		if(!$this->bogenIsActive) {
			new error("Die Aktion ist nicht mehr möglich, da der Zeitpunkt zum Eintragen überschritten wurde!");
			exit(0);
		}
	}
	
	private function showIndex() {
		// Meine Klassen
		$myName = DB::getSession()->getTeacherObject()->getKuerzel();
		
		$deadline = functions::getFormatedDateFromSQLDate($this->bogen['beobachtungsbogenDeadline']);
		
		$klassen = DB::getDB()->query("SELECT * FROM beobachtungsbogen_klasse_fach_lehrer WHERE beobachtungsbogenID='" . $this->bogenID . "' AND lehrerKuerzel LIKE '" . $myName . "' ORDER BY LENGTH(klasseName), klasseName");
		
		$klassenHTML = "";
		while($klasse = DB::getDB()->fetch_array($klassen)) {
			$isOK = "<p class=\"text-red\"><i class=\"fa fa-exclamation-triangle\"></i> Nein</p>
					<p>&raquo; <a href=\"index.php?page=beobachtungsbogen&bogenID=" . $this->bogenID . "&grade=" . $klasse['klasseName'] . "&subject=" . $klasse['fachName'] . "&mode=markAsDone\">Als erledigt markieren</a></p>";
			if($klasse['isOK'] > 0) {
				$isOK = "<p class=\"text-green\"><i class=\"fa fa-check\"></i> Ja</p>
					<p>&raquo; <a href=\"index.php?page=beobachtungsbogen&bogenID=" . $this->bogenID . "&grade=" . $klasse['klasseName'] . "&subject=" . $klasse['fachName'] . "&mode=markAsUnDone\">Als <u>nicht</u> erledigt markieren</a></p>";
			}
			
			if($this->bogenIsActive) {
				eval("\$actions = \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/index_bit_actions") . "\";");
			}
			else {
				eval("\$actions = \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/index_bit_actions_inactive") . "\";");
			}
			
			
			eval("\$klassenHTML .= \"" . DB::getTPL()->get("beobachtungsbogen/eintragen/index_bit") . "\";");
			
		}
		
		eval("echo(\"" . DB::getTPL()->get("beobachtungsbogen/eintragen/index") . "\");");
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
		return 'Beobachtungsbögen';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	
	}
	
	public static function siteIsAlwaysActive() {
		return false;
	}

	public static function onlyForSchool() {
        return [];
	}
}


?>