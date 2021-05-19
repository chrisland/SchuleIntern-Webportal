<?php


class absenzenschueler extends AbstractPage {

	/**
	 * 
	 * @var schueler[]
	 */
  private $meineSchueler = [];
  
  public function __construct() {
    parent::__construct(array("Absenzen", "Meine Absenzen"));

    $this->checkLogin();
    
    if(!DB::getSession()->isEltern() && !DB::getSession()->isPupil()) {
    	new errorPage();
    }
    
    if(DB::getSession()->isPupil()) {
    	$this->meineSchueler = [DB::getSession()->getSchuelerObject()];
    }
    
    if(DB::getSession()->isEltern()) {
    	$this->meineSchueler = DB::getSession()->getElternObject()->getMySchueler();
    }
  }

  public function execute() {
  	include_once("../framework/lib/data/absenzen/Absenz.class.php");
  	include_once("../framework/lib/data/absenzen/AbsenzBefreiung.class.php");
  	include_once("../framework/lib/data/absenzen/AbsenzBeurlaubung.class.php");
  	include_once("../framework/lib/data/absenzen/AbsenzSchuelerInfo.class.php");
  	
  	
  	if($_REQUEST['action'] == "generateEntschuldigung") {
  	    for($i = 0; $i < sizeof($this->meineSchueler); $i++) {
  	        if($this->meineSchueler[$i]->getAsvID() == $_REQUEST['schuelerID']) {
  	            $absenzen = Absenz::getAbsenzenForSchueler($this->meineSchueler[$i]);
  	            
  	            $entschuldigung = new AbsenzEntschuldigungGenerator();
  	            
  	            for($a = 0; $a < sizeof($absenzen); $a++) {
  	                if(!$absenzen[$a]->isSchriftlichEntschuldigt()) {
  	                    $entschuldigung->addAbsenz($absenzen[$a]);
  	                }
  	            }
  	            
  	            $entschuldigung->send();            
  	            
  	        }
  	    }
  	    
  	    new errorPage("Zugriffsverletzung!");
  	}
  	
  	$html = "";
  	
  	for($i = 0; $i < sizeof($this->meineSchueler); $i++) $html .= $this->showSchueler($this->meineSchueler[$i]);
  	
  	echo($this->header);
  	echo($html);
  	echo($this->footer);
  	exit(0);
  }

  private function showSchueler($schueler) {

    $absenzen = Absenz::getAbsenzenForSchueler($schueler);


      $absentenHTML = "";

      for($i = 0; $i < sizeof($absenzen); $i++) {

          $absentenHTML .= "<tr><td>" . DateFunctions::getNaturalDateFromMySQLDate($absenzen[$i]->getDateAsSQLDate()) . "</td>";
          if($absenzen[$i]->getEnddatumAsSQLDate() != $absenzen[$i]->getDateAsSQLDate()) $absentenHTML .= "<td>" . DateFunctions::getNaturalDateFromMySQLDate($absenzen[$i]->getEnddatumAsSQLDate()) . "</td>";
          else $absentenHTML .= "<td>&nbsp;</td>";

          if($absenzen[$i]->isBeurlaubung()) $absentenHTML .= "<td><i class=\"fa fa-check\"></i></td>";
          else $absentenHTML .= "<td>&nbsp;</td>";

          if($absenzen[$i]->isBefreiung()) $absentenHTML .= "<td><i class=\"fa fa-check\"></i></td>";
          else $absentenHTML .= "<td>&nbsp;</td>";

          // $absentenHTML .= "<td>" . nl2br($absenzen[$i]->getKommentar()) . "</td>";

          if(!$absenzen[$i]->isSchriftlichEntschuldigt()) {
          	$absentenHTML .= "<td><label class=\"label label-danger\">Noch nicht schriftlich entschuldigt.</label><br />";
          	
          	$lnw = $absenzen[$i]->getLeistungsnachweiseDuringAbsenzPeriod();
          	
          	if(sizeof($lnw) > 0 && DB::getSettings()->getBoolean('krankmeldung-hinweis-lnw')) {
          	    $absentenHTML .= "<b>Für diese Absenz ist ein Attest nötig, da folgende angekündigte Leistungsnachweise stattgefunden haben:</b><br />";
          	    
          	    for($b = 0; $b < sizeof($lnw); $b++) {
          	        $absentenHTML .= $lnw[$b]->getArtLangtext() . " in " . $lnw[$b]->getFach() . " bei " . $lnw[$b]->getLehrer() . "<br />";
          	    }
          	}
            
            
            if(AbsenzSchuelerInfo::hasAttestpflicht($absenzen[$i]->getSchueler(), $absenzen[$i]->getDateAsSQLDate())) {
              $absentenHTML .= "<br /><span class=\"label label-danger\">Attestpflicht</span>";
            }
            
            if(DB::getSettings()->getValue('absenzen-fristabgabe-schriftliche-entschuldigung') > 0) {
            	$absentenHTML .= "<br /><span class=\"label label-info\">Entschuldigbar bis " . DateFunctions::getNaturalDateFromMySQLDate($absenzen[$i]->getSchriftlichEntschuldigbarDate()) . "</span>";
            }
            	
            
            if(!$absenzen[$i]->isSchriftlichEntschuldigbar()) {
            	$absentenHTML .= "<br /><span class=\"label label-danger\">Absenz kann nicht mehr schriftlich entschuldigt werden.</span>";
            }
            

            $absentenHTML .= "</td>";
          }
          else {
              $absentenHTML .= "<td><label class=\"label label-success\">Entschuldigt bzw. keine schriftliche Entschuldigung nötig.</label><br />";
          }
        

      }
      
      $html = '';

      eval("\$html = \"" . DB::getTPL()->get("absenzen/schueler/schueler") . "\";");
      return $html;


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
    return array(
        array(
          'name' => "absenzen-lehreransicht",
          'typ' => BOOLEAN,
          'titel' => "Lehreransicht auf die Absenzenverwaltung aktivieren?",
          'text' => "Mit dieser Ansicht können, die Lehrer auf die Absenzenverwaltung zugreifen. (Ohne Schreibrechte)"
        ),
    	array(
    		'name' => "absenzen-lehreransicht-entschuldigungen",
    		'typ' => BOOLEAN,
    		'titel' => "Überprüfen der Entschuldigungen für Lehrer aktivieren?",
    		'text' => "Auf die Überprüfung der Entschuldigungen haben die Personen Zugriff, die auch auf das Sekretariatsmodul Zugriff haben. Für Lehrer (Klassenleiter) kann die Ansicht hier freigeschaltet werden."
    	),
    		
    	array(
    		'name' => "absenzen-lehrer-meldungaktivieren",
    		'typ' => BOOLEAN,
    		'titel' => "Meldung der Lehrer aktivieren?",
    		'text' => "Ist diese Option aktiv, können Lehrer in der Gesamtansicht der Absenzen für Lehrer einen Haken setzen, wenn die Absenzen vollständig und richtig sind. (z.B. direkt aus dem Unterricht heraus)"
   		),
    );
  }


  public static function getSiteDisplayName() {
    return 'Schüleransicht (Absenzen)';
  }

  /**
   * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
   * @return array(array('groupName' => '', 'beschreibung' => ''))
   */
  public static function getUserGroups() {
    return [];
  }

  public static function siteIsAlwaysActive() {
    return false;
  }
  
  public static function getAdminGroup() {
  	return 'Webportal_Absenzen_Lehrer_Admin';
  }
  
  public static function dependsPage() {
  	return ['absenzensekretariat', 'absenzenberichte','absenzenstatistik'];
  }
  
  public static function userHasAccess($user) {
  	if($user->isAdmin()) return true;
  	
  	if($user->isMember(self::getAdminGroup())) return true;
  	  	
  	if($user->isMember('Webportal_Absenzen_Sekretariat')) {
  		return true;
  	}
  	
  	if($user->isTeacher() && (DB::getSettings()->getBoolean("absenzen-lehreransicht") || DB::getSettings()->getBoolean("absenzen-lehreransicht-entschuldigungen"))) {
  		return true;
  	}
  	
  	return false;
  }
  
  public static function hasAdmin() {
  	return false;
  }
  
  public static function getAdminMenuGroup() {
  	return 'Absenzenverwaltung';
  }
  
  public static function getAdminMenuGroupIcon() {
  	return 'fa fas fa-procedures';
  }
  

}


?>
