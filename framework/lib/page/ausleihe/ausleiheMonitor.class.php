<?php


class ausleiheMonitor extends AbstractPage {

	private $info;
	
	private $isAdmin = false;

	public function __construct() {
		$this->needLicense = true;
		
		parent::__construct(array(), true);
		
		if($_REQUEST['viewcode'] != DB::getSettings()->getValue("ausleiheMonitor-viewCode")) die("No Access. Wrong viewcode!");
	}

	public function execute() {
		
		if(isset($_REQUEST['objektID'])) {
			
			$objekt = DB::getDB()->query_first("SELECT * FROM ausleihe_objekte WHERE isActive=1 AND objektID='" . DB::getDB()->escapeString($_REQUEST['objektID']) . "'");
			
			// firstDay = TTMMYYYY
			if(!isset($_REQUEST['firstDay'])) {
				$thisweek = strtotime("this week");
				$firstDayWeek = date("j", $thisweek);
				$monthFirstDayWeek = date("n", $thisweek);
				$yearFirstDayWeek = date("Y", $thisweek);
				$_REQUEST['firstDay'] = date("dmY",$thisweek);
			}
			else {
				$firstDayWeekTime = mktime(0,0,0,
						substr($_REQUEST['firstDay'], 2,2),
						substr($_REQUEST['firstDay'], 0,2),
						substr($_REQUEST['firstDay'], 4,4));
				$firstDayWeek = date("j", $firstDayWeekTime);
				$monthFirstDayWeek = date("n", $firstDayWeekTime);
				$yearFirstDayWeek = date("Y", $firstDayWeekTime);
			}
			
			if($objekt['objektID'] > 0) {
				if(isset($_REQUEST['action'])) $action = $_REQUEST['action'];
				else $action = "index";
					
				switch($action) {
					case "index":
					default:
						$html = $this->showWeekForObject($firstDayWeek, $monthFirstDayWeek, $yearFirstDayWeek, $objekt);
						
						eval("\$content = \"" . DB::getTPL()->get("ausleihe/ausleiheMonitor") . "\";");
					break;
				}
			}
			else {
				die("Malformed Request: objektID invalid or not active.");
			}
		}
		else {
		
			$content = "Hier können die iPad Koffer reserviert werden.";
		
		}
		
		echo $content;
	}
	
	// mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
	private function showWeekForObject($firstDayWeek, $monthFirstDayWeek, $yearFirstDayWeek, $objekt) {
		// $objekt ist Array
		$timeFirstDayWeek = mktime(0,0,0,$monthFirstDayWeek,$firstDayWeek, $yearFirstDayWeek);
		$secPerDay = 24 * 60 * 60 + 2; // Schaltsekunden umgehen, es geht uns ja nur um die Tage, nicht um die Zeit.
		
		if(date("w",$firstDayWeek) != 4) die("Falsches Datumsformat! Es muss ein Montag angegeben werden: " . date("w",$firstDayWeek));
		
		$html = "<table border=\"0\" style=\"width:100%\"><tr><td colspan=\"3\" align=\"center\"><h2>Woche vom " . date("d.m.Y",$timeFirstDayWeek) . "</h2></td></tr>";
		$prevWeek = $timeFirstDayWeek + 20;
		$prevWeek -= 7 * $secPerDay;
		
		$nextWeek = $timeFirstDayWeek + 20;
		$nextWeek += 7 * $secPerDay;
		
		$showStunden = 10;
		
		
		
		$html .= "<tr><td><font size=+3><a href=\"index.php?page=ausleiheMonitor&viewcode=oief3efihjeofijoijoijoij&objektID={$objekt['objektID']}&firstDay=" . date("dmY", $prevWeek) . "\">Woche zurück</a></td>";
		$html .= "<td align=\"center\"><font size=+3><a href=\"index.php?page=ausleiheMonitor&viewcode=oief3efihjeofijoijoijoij&objektID={$objekt['objektID']}\">Zur aktuelle Woche</a></td>";
		$html .= "<td align=\"right\"><font size=+3><a href=\"index.php?page=ausleiheMonitor&viewcode=oief3efihjeofijoijoijoij&objektID={$objekt['objektID']}&firstDay=" . date("dmY", $nextWeek) . "\">Woche weiter</a></td></tr><tr><td colspan=\"3\">";
		
		$dates = array();
		$datumDisplay = array();
		
		for($i = 0; $i < 5; $i++) {
			$timeDay = $timeFirstDayWeek + ($i * $secPerDay);
			$dates[] = "'" . date("Y-m-d",$timeDay) . "'";
			$datumDisplay[] = date("d.m.Y",$timeDay);
			$datesAusleiheData[] = date("Y-m-d",$timeDay);
		}
		
		$ausleihData = array();
		if(sizeof($dates) > 0) {
			$ausleihen = DB::getDB()->query("SELECT *, (SELECT userLastName FROM users WHERE userID=ausleihe_ausleihe.ausleiheAusleiherUserID LIMIT 1) AS ausleiheAusleiher FROM ausleihe_ausleihe WHERE ausleiheObjektID='" . $objekt['objektID'] . "' AND ausleiheDatum IN (" . implode(",",$dates) . ")");
			while($a = DB::getDB()->fetch_array($ausleihen)) {
				$ausleihData[$a['ausleiheDatum']][$a['ausleiheStunde']][$a['ausleiheObjektIndex']] = $a;
			}		
		}

		$html .= "<table style=\"border-collapse: collapse; border: 1px solid black;width:100%\">";
		$html .= "<tr><td valign=\"center\" align=\"center\">Stunde</td>";
		for($i = 0; $i < sizeof($datumDisplay); $i++) {
			if($objekt['objektAnzahl'] > 1) $colspan = " colspan=\"{$objekt['objektAnzahl']}\"";
			else $colspan = "";
			$html .= "<td style=\" border: 0px solid black;border-left-width: 3px;\"align=center$colspan><span style=\"font-size:16pt;\">" . $this->getDayName($i) . "<br /><small>" . $datumDisplay[$i] . "</small></td>";
		}
		$html .= "</tr>";
		
		$html .= "<tr><td style=\" border: 1px solid black;\">&nbsp;</td>";
		for($i = 0; $i < sizeof($datumDisplay); $i++) {
			for($v = 1; $v <= $objekt['objektAnzahl']; $v++) {
				$html .= "<td align=\"center\" style=\" border: 1px solid black;" . (($v == 1) ? ("border-left-width: 3px;") : ("")) . "\"><span style=\"font-size:12pt;\">" . $objekt['objektName'] . " $v</td>";
			}
		}
		$html .= "</tr>";
		
		$bgColor = "#CECECE";
		for($s = 1; $s <= $showStunden; $s++) {
			$html .= "<tr style=\"background-color: $bgColor;\" height=\"40\"><td style=\"border: 0px solid black;border-bottom-width:2px;vertical-align: middle;\" align=\"center\">$s</td>";
			
			for($i = 0; $i < sizeof($datesAusleiheData); $i++) {
				// Ferien prüfen
				$ferien = DB::getDB()->query_first("SELECT * FROM kalender_ferien WHERE ferienStart<='" . $datesAusleiheData[$i] . "' AND ferienEnde >= '" . $datesAusleiheData[$i] . "'");
				
				if($ferien['ferienID'] > 0 && $s == 1) {
					$html .= "<td rowspan=\"" . $showStunden . "\" colspan=\"" . $objekt['objektAnzahl'] . "\" align=\"center\" style=\"vertical-align: middle;\">" . $ferien['ferienName'] . "</td>";
				}
				elseif($ferien['ferienID'] > 0) {
					// Nix
				}
				else {
					for($v = 1; $v <= $objekt['objektAnzahl']; $v++) {
						if($ausleihData[$datesAusleiheData[$i]][$s][$v]['ausleiheID'] > 0) {
							$html .= "<td align=\"center\" style=\"background-color: $bgColor; border: 1px solid black;border-bottom-width:2px;" . (($v == 1) ? ("border-left-width: 3px;") : ("border-left-style: dotted;")) . "vertical-align: middle;\" valign=\"middle\"><span style=\"color:#FF1010;font-size:18pt;\">" .
							$ausleihData[$datesAusleiheData[$i]][$s][$v]['ausleiheAusleiher'] .	"<br />" .
							$ausleihData[$datesAusleiheData[$i]][$s][$v]['ausleiheKlasse'] . "</td>\n";
						}
						else {
							$html .= "<td align=\"center\" style=\"background-color: $bgColor; border: 1px solid black;border-bottom-width:2px;" . (($v == 1) ? ("border-left-width: 3px;") : ("border-left-style: dotted;")) . "vertical-align: middle;\" valign=\"middle\"><span style=\"color:green;font-size:18pt;\">-- frei --</td>\n";
						}
					}
				}
			}
			
			if($bgColor == "#CECECE") $bgColor = "#FEFEFE";
			else $bgColor = "#CECECE";
		}
				
		$html .= "</table></td></tr></table>";


		return $html;
	}
	
	private function getDayName($i) {
		return (($i == 0) ? ("Montag") : (($i== 1) ? ("Dienstag") : (($i == 2) ? ("Mittwoch") : (($i == 3) ? ("Donnerstag") : (($i == 4) ? ("Freitag") : ("X"))))));
	}
	
	public static function hasSettings() {
		return true;
	}
	
	public static function getSiteDisplayName() {
		return "Objektausleihe - Anzeige für Monitor";
	}
	
	public static function getSettingsDescription() {
		return array(
				array(
						"name" => "ausleiheMonitor-viewCode",
						"typ" => "ZEILE",
						"titel" => "Schlüssel zur Anzeige",
						"text" => "Geheimer Schlüssel, damit der Monitor auch ohne Zugangsdaten den Ausleihstatus anzeigen kann."
				)
		);
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
		return array(

		);
	}
}


?>