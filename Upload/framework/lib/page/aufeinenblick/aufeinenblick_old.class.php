<?php

class aufeinenblick extends AbstractPage {

  private $mySettings = array(
    "aufeinenblinkSettingsID" => 0,
    "aufeinenblickHourCanceltoday" => 15,
    "aufeinenblickShowVplan" => 1,
    "aufeinenblickShowCalendar" => 1,
    "aufeinenblickShowStundenplan" => 0
  );

  const AUFEINENBLICK_ADMINGROUP = 'Webportal_Aufeinenblick_admin';

  protected $helpPage = "";

  public function __construct() {

    $this->needLicense = false;

    parent::__construct ( array (
      "Auf einen Blick"
    ) );

    $this->checkLogin();

    if(!DB::getSession()->isTeacher() && !DB::getSession()->isPupil() && !DB::getSession()->isEltern()) {
      
      stundenplan::userHasAccess($user);
        
      eval("DB::getTPL()->out(\"" . DB::getTPL()->get("index") . "\");");
      PAGE::kill(true);
			//exit(0);
    }

    $this->loadMySettings();
  }

  private function loadMySettings() {
    $mySettings = DB::getDB()->query_first("SELECT * FROM aufeinenblick_settings WHERE aufeinenblickUserID='" . DB::getUserID() . "'");

    if($mySettings['aufeinenblickSettingsID'] > 0) {
      $this->mySettings = $mySettings;
    }

  }

  public function execute() {

    if($_GET['mode'] == "settings") {
      // Einstellungen

      $hourSelect = "";

      for($i = 1; $i < 24; $i++) {
        $hourSelect .= "<option value=\"" . $i . "\"" . (($this->mySettings['aufeinenblickHourCanceltoday'] == $i) ? (" selected=\"selected\"") : ("")) . "\">" . $i . ":00 Uhr</option>\n";
      }

      $vplanShowSelect = "<option value=\"1\"" . (($this->mySettings['aufeinenblickShowVplan'] > 0) ? (" selected=\"selected\"") : ("")) . ">Ausklappen</option>";
      $vplanShowSelect .= "<option value=\"0\"" . (($this->mySettings['aufeinenblickShowVplan'] == 0) ? (" selected=\"selected\"") : ("")) . ">Einklappen</option>";

      $stundenplanShowSelect = "<option value=\"1\"" . (($this->mySettings['aufeinenblickShowStundenplan'] > 0) ? (" selected=\"selected\"") : ("")) . ">Ausklappen</option>";
      $stundenplanShowSelect .= "<option value=\"0\"" . (($this->mySettings['aufeinenblickShowStundenplan'] == 0) ? (" selected=\"selected\"") : ("")) . ">Einklappen</option>";

      $calendarShowSelect = "<option value=\"1\"" . (($this->mySettings['aufeinenblickShowCalendar'] > 0) ? (" selected=\"selected\"") : ("")) . ">Ausklappen</option>";
      $calendarShowSelect .= "<option value=\"0\"" . (($this->mySettings['aufeinenblickShowCalendar'] == 0) ? (" selected=\"selected\"") : ("")) . ">Einklappen</option>";


      if($_GET['save'] > 0) {
        // Abspeichern
        for($i = 1; $i < 24; $i++) {
          if($_POST['canceltoday'] ==  $i) {
            $this->mySettings['aufeinenblickHourCanceltoday'] = $i;
            break;
          }
        }

        if($_POST['vplanShow'] > 0) {
          $this->mySettings['aufeinenblickShowVplan'] = 1;
        }
        else {
          $this->mySettings['aufeinenblickShowVplan'] = 0;
        }

        if($_POST['calendarShow'] > 0) {
          $this->mySettings['aufeinenblickShowCalendar'] = 1;
        }
        else {
          $this->mySettings['aufeinenblickShowCalendar'] = 0;
        }

        if($_POST['stundenplanShow'] > 0) {
          $this->mySettings['aufeinenblickShowStundenplan'] = 1;
        }
        else {
          $this->mySettings['aufeinenblickShowStundenplan'] = 0;
        }

        if($this->mySettings['aufeinenblickSettingsID'] > 0) {
          // Update
          DB::getDB()->query("UPDATE aufeinenblick_settings SET
            aufeinenblickHourCanceltoday = {$this->mySettings['aufeinenblickHourCanceltoday']},
            aufeinenblickShowVplan = {$this->mySettings['aufeinenblickShowVplan']},
            aufeinenblickShowCalendar = {$this->mySettings['aufeinenblickShowCalendar']},
            aufeinenblickShowStundenplan = {$this->mySettings['aufeinenblickShowStundenplan']}
          WHERE
            aufeinenblickSettingsID={$this->mySettings['aufeinenblickSettingsID']}
          ");
        }
        else {
          DB::getDB()->query("INSERT INTO aufeinenblick_settings
            (aufeinenblickUserID,
            aufeinenblickHourCanceltoday,
            aufeinenblickShowVplan,
            aufeinenblickShowCalendar,
            aufeinenblickShowStundenplan)
              values
            (
              " . DB::getSession()->getUserID() . ",
              " . $this->mySettings['aufeinenblickHourCanceltoday'] . ",
              " . $this->mySettings['aufeinenblickShowVplan'] . ",
              " . $this->mySettings['aufeinenblickShowCalendar'] . ",
              " . $this->mySettings['aufeinenblickShowStundenplan'] . "
            )
          ");
        }

        header("Location: index.php");
        exit(0);
      }


      eval("echo(\"" . DB::getTPL()->get("aufeinenblick/settings/index") . "\");");
      PAGE::kill(true);
      //exit(0);
    }

    Stundenplan::getCurrentStunde();


    // Stundenplan heute laden

    // $stundenplan = stundenplandata::getCurrentStundenplan();

    // if($stundenplan == null) {
    // eval("echo(\"" . DB::getTPL()->get("aufeinenblick/nocurrentstundenplan") . "\");");
    // PAGE::kill(true);
    // exit(0);
    // }

    // 0: Sonntag ... 6: Samstag
    // Im Stundenplan: 0: Montag
    $dayOfWeek = date ( "w" );
    $dayOfWeekFinal = date ( "w" );

    if ($dayOfWeek == 0)
      $dayOfWeek = 6;
    else
      $dayOfWeek --;

    $dayOfWeekOne = 0;
    $dayOfWeekTwo = 0;

    $messageNextDay = "";

    if ($dayOfWeek > 4) {
      // Samstag oder Sonntag
      $dayOfWeekOne = 0;
      $dayOfWeekTwo = 1;

      if ($dayOfWeek == 5) {
        // Samstag
        $date = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 2 DAY) AS DATUM" );
        $dateDay1 = $date ['DATUM'];

        $date = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 3 DAY) AS DATUM" );
        $dateDay2 = $date ['DATUM'];
      }

      if ($dayOfWeek == 6) {
        // Samstag
        $date = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 1 DAY) AS DATUM" );
        $dateDay1 = $date ['DATUM'];

        $date = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 2 DAY) AS DATUM" );
        $dateDay2 = $date ['DATUM'];
      }
    } else {
      if (date ( "H" ) < $this->mySettings['aufeinenblickHourCanceltoday']) {

        // Vor 16 Uhr normal machen.
        $dayOfWeekOne = $dayOfWeek;

        $date = DB::getDB ()->query_first ( "SELECT CURDATE() AS DATUM" );
        $dateDay1 = $date ['DATUM'];

        if (($dayOfWeek + 1) > 4) {
          $dayOfWeekTwo = 0;

          if (($dayOfWeek + 1) == 5) {
            // Samstag
            $date = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 3 DAY) AS DATUM" );
          } else {
            // Sonntag
            $date = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 2 DAY) AS DATUM" );
          }

          $dateDay2 = $date ['DATUM'];
        } else {
          $date = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 1 DAY) AS DATUM" );
          $dateDay2 = $date ['DATUM'];

          $dayOfWeekTwo = $dayOfWeek + 1;
        }
      } else {
        $messageNextDay = "<div class=\"callout callout-info\">Ab " . $this->mySettings['aufeinenblickHourCanceltoday'] . ":00 Uhr werden die nächsten zwei Tage angezeigt. <small>Die Uhrzeit kann über \"Diese Seite anpassen\" eingestellt werden.</small></div>";

        $dayOfWeekOne = $dayOfWeek + 1;

        if ($dayOfWeekOne > 4) { // Am Freitag ist der nächste Tag Samstag, also: Montag und Dienstag
          $dayOfWeekOne = 0;
          $dayOfWeekTwo = 1;

          $dateDay1 = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 3 DAY) AS DATUM" );
          $dateDay2 = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 4 DAY) AS DATUM" );
          $dateDay1 = $dateDay1 ['DATUM'];
          $dateDay2 = $dateDay2 ['DATUM'];
        } elseif ($dayOfWeekOne == 4) { // Donnerstag
          $dayOfWeekTwo = 0;
          $dateDay1 = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 1 DAY) AS DATUM" );
          $dateDay2 = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 4 DAY) AS DATUM" );
          $dateDay1 = $dateDay1 ['DATUM'];
          $dateDay2 = $dateDay2 ['DATUM'];
        } else {
          $dayOfWeekTwo = $dayOfWeekOne + 1;
          $dateDay1 = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 1 DAY) AS DATUM" );
          $dateDay2 = DB::getDB ()->query_first ( "SELECT DATE_ADD(CURDATE(),INTERVAL 2 DAY) AS DATUM" );
          $dateDay1 = $dateDay1 ['DATUM'];
          $dateDay2 = $dateDay2 ['DATUM'];
        }
      }
    }

    // Ferien?

    $ferien1 = DB::getDB ()->query_first ( "SELECT * FROM kalender_ferien WHERE ferienStart <= '$dateDay1' AND ferienEnde >= '$dateDay1'" );
    $ferien2 = DB::getDB ()->query_first ( "SELECT * FROM kalender_ferien WHERE ferienStart <= '$dateDay2' AND ferienEnde >= '$dateDay2'" );

    if ($ferien1 ['ferienID'] > 0) {
      // Beide Tage
      $dateDay1 = DB::getDB ()->query_first ( "SELECT DATE_ADD((SELECT ferienEnde FROM kalender_ferien WHERE ferienID='" . $ferien1 ['ferienID'] . "' LIMIT 1),INTERVAL 1 DAY) AS DATUM" );
      $dateDay1 = $dateDay1 ['DATUM'];

      $dateDay2 = DB::getDB ()->query_first ( "SELECT DATE_ADD((SELECT ferienEnde FROM kalender_ferien WHERE ferienID='" . $ferien1 ['ferienID'] . "' LIMIT 1),INTERVAL 2 DAY) AS DATUM" );
      $dateDay2 = $dateDay2 ['DATUM'];

      list ( $year, $month, $day ) = explode ( "-", $dateDay1 );
      $dayOfWeekOne = date ( "w", mktime ( 23, 10, 10, $month, $day, $year ) );

      if ($dayOfWeekOne == 0)
        $dayOfWeekOne = 6;
      else
        $dayOfWeekOne --;

      list ( $year, $month, $day ) = explode ( "-", $dateDay2 );
      $dayOfWeekTwo = date ( "w", mktime ( 23, 10, 10, $month, $day, $year ) );

      if ($dayOfWeekTwo == 0)
        $dayOfWeekTwo = 6;
      else
        $dayOfWeekTwo --;
    } elseif ($ferien2 ['ferienID'] > 0) {
      $dateDay2 = DB::getDB ()->query_first ( "SELECT DATE_ADD((SELECT ferienEnde FROM kalender_ferien WHERE ferienID='" . $ferien2 ['ferienID'] . "' LIMIT 1),INTERVAL 1 DAY) AS DATUM" );
      $dateDay2 = $dateDay2 ['DATUM'];

      // Wochentag bestimmen
      list ( $year, $month, $day ) = explode ( "-", $dateDay2 );
      $dayOfWeekTwo = date ( "w", mktime ( 23, 10, 10, $month, $day, $year ) );

      if ($dayOfWeekTwo == 0)
        $dayOfWeekTwo = 6;
      else
        $dayOfWeekTwo --;
    }

    $HTML = "";

    if (DB::getSession ()->isTeacher ()) {

      $stundenplanLehrer = stundenplandata::getStundenplanAtDate ( $dateDay1 );
      $stundenplanLehrer = $stundenplanLehrer->getPlan ( array (
          "teacher",
          DB::getSession ()->getTeacherObject()->getKuerzel()
      ) );

      $stundenplanDay1 = $stundenplanLehrer [$dayOfWeekOne];

      $HTML .= $this->getToday ( array (
          $stundenplanDay1
      ), "Lehrer", $dateDay1, $dayOfWeekOne, array (
          DB::getSession ()->getTeacherObject()->getKuerzel()
      ) );

      $stundenplanLehrer = stundenplandata::getStundenplanAtDate ( $dateDay2 );
      $stundenplanLehrer = $stundenplanLehrer->getPlan ( array (
          "teacher",
          DB::getSession ()->getTeacherObject()->getKuerzel()
      ) );
      $stundenplanDay2 = $stundenplanLehrer [$dayOfWeekTwo];
      $HTML .= $this->getToday ( array (
          $stundenplanDay2
      ), "Lehrer", $dateDay2, $dayOfWeekTwo, array (
          DB::getSession ()->getTeacherObject()->getKuerzel()
      ) );
    } else {
      if(DB::getSession()->isPupil()) $grades = array(DB::getSession()->getSchuelerObject()->getKlasse());
      else if(DB::getSession()->isEltern()) $grades = DB::getSession()->getElternObject()->getKlassenAsArray();

      $plaeneDay1 = array ();
      $plaeneDay2 = array ();

      for($i = 0; $i < sizeof ( $grades ); $i ++) {

        $stPlan = stundenplandata::getStundenplanAtDate ( $dateDay1 );

        $stPlan = $stPlan->getPlan ( array (
            "grade",
            $grades [$i] . "%"
        ) );

        $plaeneDay1 [] = $stPlan [$dayOfWeekOne];

        $stPlan = stundenplandata::getStundenplanAtDate ( $dateDay2 );

        $stPlan = $stPlan->getPlan ( array (
            "grade",
            $grades [$i] . "%"
        ) );

        $plaeneDay2 [] = $stPlan [$dayOfWeekTwo];
      }

      if(!is_array($grades)) $grades = array();

      $HTML .= $this->getToday ( $plaeneDay1, implode(", ",$grades), $dateDay1, $dayOfWeekOne, $grades );

      $HTML .= $this->getToday ( $plaeneDay2, implode(", ",$grades), $dateDay2, $dayOfWeekTwo, $grades );
    }

    // eval("\$indexStatus = \"".DB::getTPL()->get("index_loggedin")."\";");


    $showKlassentagebuchButton = false;

    if($this->isActive("klassentagebuch")) {
        if(DB::getSession()->isEltern() && DB::getSettings()->getBoolean('klassentagebuch-eltern-klassentagebuch'))
            $showKlassentagebuchButton = true;

         else if(DB::getSession()->isTeacher())
             $showKlassentagebuchButton = true;
         else if(DB::getSession()->isPupil() && DB::getSettings()->getBoolean('klassentagebuch-schueler-klassentagebuch'))
             $showKlassentagebuchButton = true;

         else if(DB::getSession()->isMember('Webportal_Klassentagebuch_Lesen'))
             $showKlassentagebuchButton = true;
         
        if(DB::getSettings()->getBoolean('klassentagebuch-lehrertagebuch') && DB::getSession()->isTeacher())
            $showLehrerTagebuchButton = true;
    }


    eval ( "echo(\"" . DB::getTPL ()->get ( "aufeinenblick/index" ) . "\");" );
  }
  private function getToday($plan, $title, $datum, $dayOfWeek, $planTitles) {
    $datumTermine = $datum;

    $datumShow = functions::getDayName ( $dayOfWeek ) . ", " . functions::getFormatedDateFromSQLDate ( $datum );

    // $currentStundenplanID = stundenplandata::getCurrentStundenplanID();

    $maxCells = array ();

    for($i = 0; $i < sizeof ( $plan ); $i ++) {
      for($s = 0; $s < sizeof ( $plan [$i] ); $s ++) {
        if (sizeof ( $plan [$i] [$s] ) > $maxCells [$i]) {
          $maxCells [$i] = sizeof ( $plan [$i] [$s] );
        }
      }
    }

    $stundenplanHTML = "<table class=\"table table-striped\">";
    $stundenplanHTML .= "<tr><th width=\"5%\">Stunde</th>";

    for($i = 0; $i < sizeof ( $plan ); $i ++) {
      $stundenplanHTML .= "<th colspan=\"{$maxCells[$i]}\">" . functions::getDayName ( $dayOfWeek ) . "<br />" . $planTitles [$i] . "</th>";
    }

    $stundenplanHTML .= "</tr>";

    for($i = 0; $i < sizeof ( $plan [0] ); $i ++) {
      $stundenplanHTML .= "<tr><td>" . ($i + 1) . "</td>";

      for($p = 0; $p < sizeof ( $plan ); $p ++) {

        $usedCols = 0;
        for($s = 0; $s < sizeof ( $plan [$p] [$i] ); $s ++) {
          $usedCols ++;

          if ($s == (sizeof ( $plan [$p] [$i] ) - 1)) {
            $colspan = $maxCells [$p] - $usedCols + 1;
          } else
            $colspan = 1;

          $stundenplanHTML .= "<td  width=\"10\" colspan=\"$colspan\"><b>" . $plan [$p] [$i] [$s] ['subject'] . "</b> - " . $plan [$p] [$i] [$s] ['grade'] . "<br />";

          $stundenplanHTML .= $plan [$p] [$i] [$s] ['room'] . " - " . $plan [$p] [$i] [$s] ['teacher'] . "</td>";
        }

        if ($usedCols == 0)
          $stundenplanHTML .= "<td colspan=\"{$maxCells[$p]}\">&nbsp;<br />&nbsp;</td>";
      }

      $stundenplanHTML .= "</tr>";
    }

    /**
     * for($i = 0; $i < sizeof($plan[0]); $i++) {
     * $stundenplanHTML .
     * = "<th>" . ($i+1) . ". Stunde</th>";
     * }
     * $stundenplanHTML .= "</tr>";
     *
     * for($p = 0; $p < sizeof($plan); $p++) {
     *
     * $stundenplanHTML .= "<tr>";
     *
     * $stundenplanHTML .= "<td><b>" . functions::getDayName($dayOfWeek) . "</b><br />" . $planTitles[$p] . "</td>";
     *
     * for($i = 0; $i < sizeof($plan[$p]); $i++) {
     * $colsUsed = 0;
     * $stundenplanHTML .= "<td>";
     *
     * if(sizeof($plan[$i] == 0)) $stundenplanHTML .= "&nbsp;";
     *
     * for($s = 0; $s < sizeof($plan[$p][$i]); $s++) {
     *
     * if($s > 0) $stundenplanHTML .= "<hr noshade>";
     * $stundenplanHTML .= $plan[$p][$i][$s]['subject'] . " - " . $plan[$p][$i][$s]['grade'] . "<br />";
     *
     * $stundenplanHTML .= $plan[$p][$i][$s]['room'] . " - " . $plan[$p][$i][$s]['teacher'];
     *
     *
     * }
     * $stundenplanHTML .= "</td>";
     * }
     *
     * $stundenplanHTML .= "</tr></td>";
     * }
     */

    $stundenplanHTML .= "</table>";

    // Vertretungsplan

    $planFound = false;
    $plaene = DB::getDB ()->query ( "SELECT * FROM vplan WHERE " . ((DB::getSession ()->isTeacher ()) ? (" vplanName LIKE 'lehrer%'") : (" vplanName LIKE 'schueler%'")) );
    while ( $plan = DB::getDB ()->fetch_array ( $plaene ) ) {
      $lastUpdate = $plan ['vplanUpdate'];

      $date = $plan ['vplanDate'];
      $date = explode ( ", ", $date );

      list ( $day, $month, $year ) = explode ( ".", $date [1] );

      list ( $datumYear, $datumMonth, $datumDay ) = explode ( "-", $datum );

      if ($day == $datumDay && $month == $datumMonth && $year == $datumYear) {
        // Plan gefunden
        $planFound = true;

        // Eigene Einträge suchen

        if (DB::getSession ()->isTeacher ()) {
          if(DB::getGlobalSettings()->stundenplanSoftware == "SPM++") {
              if(DB::getGlobalSettings()->stundenplanSoftwareVersion == "2" || DB::getGlobalSettings()->stundenplanSoftwareVersion == "1") {

              $search = DB::getSession ()->getTeacherObject()->getKuerzel();


              $search = ">" . strtolower ( $search );
            }
            else {

              $search = DB::getSession ()->getTeacherObject()->getName();


              $search = ">" . strtolower ( $search );
            }


            $header = "<h4>Meine Vertretungen</h4><table class=\"table table-striped\"><tr><th>Vertretung</th><th>Stunde</th><th>Klasse</th><th>Fach</th><th>Lehrer</th><th>Raum</th><th>Sonstiges</tr>\r\n</th></tr>";

          }

          if(DB::getGlobalSettings()->stundenplanSoftware == "UNTIS") {
            $search = DB::getSession ()->getTeacherObject()->getKuerzel();
            $search = ">" . strtolower ( $search );

            $header = "<table class=\"mon_list\" style=\"width:50%\">";
            // $header .= '<tr class="list"><th class="list" align="center">Vertreter</th><th class="list" align="center">Stunde</th><th class="list" align="center">Klasse(n)</th><th class="list" align="center">Fach</th><th class="list" align="center">Raum</th><th class="list" align="center">Art</th><th class="list" align="center">(Fach)</th><th class="list" align="center">(Lehrer)</th><th class="list" align="center">Vertr. von</th><th class="list" align="center">(Le.) nach</th></tr>';
            $header .= explode("\n", $plan ['vplanContent'])[1];
          }

          if(DB::getGlobalSettings()->stundenplanSoftware == "TIME2007") {
          	$search = DB::getSession()->getTeacherObject()->getName();

          	$header = "<h4>Meine Vertretungen</h4><table class=\"table table-bordered\"><tr><th>Lehrer</th><th>Std.</th><th>Klasse</th><th>Fach</th><th>Raum</th><th>für</th><th>Bemerkung</th></tr>";


          }


        } else if(DB::getSession()->isPupil() ) {
          $search = DB::getSession ()->getPupilObject()->getKlasse();
          $search = strtolower ( $search );
          $searchText = strtoupper ( $searchText );

          if(DB::getGlobalSettings()->stundenplanSoftware == "UNTIS") {
            $header = "<table class=\"mon_list\" style=\"width:50%\">";
            $header .= explode("\n", $plan ['vplanContent'])[1];
            // $header .= '<tr class="list"><th class="list" align="center">Klasse(n)</th><th class="list" align="center">Stunde</th><th class="list" align="center">Vertreter</th><th class="list" align="center">Fach</th><th class="list" align="center">Raum</th><th class="list" align="center">Art</th><th class="list" align="center">(Fach)</th><th class="list" align="center">(Lehrer)</th><th class="list" align="center">Vertr. von</th><th class="list" align="center">(Le.) nach</th></tr>';

          }

          if(DB::getGlobalSettings()->stundenplanSoftware == "SPM++") {
            $header = "<h4>Meine Vertretungen</h4><table class=\"table table-striped\"><tr><th>Vertretung</th><th>Stunde</th><th>Klasse</th><th>Fach</th><th>Lehrer</th><th>Raum</th><th>Sonstiges</tr>\r\n</th></tr>";
          }

          if(DB::getGlobalSettings()->stundenplanSoftware == "TIME2007") {
              $searchText = strtoupper( $searchText );
          	$header = "<h4>Meine Vertretungen</h4><table class=\"table table-bordered\"><tr><th>Klasse</th><th>Std.</th><th>Lehrer/Fach</th><th>vertr. durch</th><th>Fach</th><th>Raum</th><th>Bemerkung</th></tr>";


          }
        }
        else if(DB::getSession()->isEltern()) {

          if(DB::getGlobalSettings()->stundenplanSoftware == "UNTIS") {
            $header = "<table class=\"mon_list\" style=\"width:50%\">";
            $header .= explode("\n", $plan ['vplanContent'])[1];
            // $header .= '<tr class="list"><th class="list" align="center">Klasse(n)</th><th class="list" align="center">Stunde</th><th class="list" align="center">Vertreter</th><th class="list" align="center">Fach</th><th class="list" align="center">Raum</th><th class="list" align="center">Art</th><th class="list" align="center">(Fach)</th><th class="list" align="center">(Lehrer)</th><th class="list" align="center">Vertr. von</th><th class="list" align="center">(Le.) nach</th></tr>';

          }

          if(DB::getGlobalSettings()->stundenplanSoftware == "SPM++") {
            $header = "<h4>Meine Vertretungen</h4><table class=\"table table-striped\"><tr><th>Vertretung</th><th>Stunde</th><th>Klasse</th><th>Fach</th><th>Lehrer</th><th>Raum</th><th>Sonstiges</tr>\r\n</th></tr>";
          }
          
          
          if(DB::getGlobalSettings()->stundenplanSoftware == "TIME2007") {
              $searchText = strtoupper( $searchText );
              $header = "<h4>Meine Vertretungen</h4><table class=\"table table-bordered\"><tr><th>Klasse</th><th>Std.</th><th>Lehrer/Fach</th><th>vertr. durch</th><th>Fach</th><th>Raum</th><th>Bemerkung</th></tr>";
              
              
          }

          $search = DB::getSession()->getElternObject()->getKlassenAsArray();
          // Multi - Klassen
          $multisearch = true;

          if ($multisearch)
            $searchText = implode ( ", ", $search );
          else
            $searchText = $search;
        }


        $content = $plan ['vplanContent'];

        $update = "(Letzte Aktualisierung: " . $plan ['vplanUpdate'] . ")";

        $ownData = array();

        $cont = explode ( "\n", str_replace ( "\r", "", $content ) );
        for($c = 0; $c < sizeof ( $cont ); $c ++) {
          if ($multisearch) {
            for($s = 0; $s < sizeof ( $search ); $s ++) {
              if (strpos ( strtolower ( $cont [$c] ), strtolower($search [$s]) ) > 0) {
                $ownData [] = $cont [$c];
              }
            }
          } else {
            if (strpos ( strtolower ( $cont [$c] ), strtolower($search) ) > 0) {
              $ownData [] = $cont [$c];
            }
          }
        }
        
        /*
         * 				if(!$deleteOwnDataTable && !$this->noSearch ) {
					for($c = 0; $c < sizeof($cont); $c++) {
						if($multisearch) {
							for($s = 0; $s < sizeof($search); $s++) {
								if(strpos(strtolower($cont[$c]), strtolower($search[$s])) > 0) {
									$ownData[] = $cont[$c];
								}
							}
						}
						else {
							if(strpos(strtolower($cont[$c]), strtolower($search)) > 0) {
								$ownData[] = $cont[$c];
							}
						}
					}
					
				}
         */

        if (sizeof ( $ownData ) == 0) {
          $ownData = "<tr><td align=\"center\" colspan=\"10\">-- Keine --</td></tr>";
        } else {
          $ownData = implode ( "\r\n", $ownData );
        }

        $ownData .= "</table><br />";

        $vertretungsplanHTML = $header . $ownData . str_replace ( "width=\"100%\"", "", $plan ['vplanInfo'] );
      }

      if (! $planFound) {
        $vertretungsplanHTML = "<p class=\"text-red\">Für diesen Tag ist noch kein Vertretungsplan verfügbar.</p>";
        $update = "";
      }

      // Klassenkalender

      $lnwHTML = "";


      $schultermine = Schultermin::getAll($datum,$datum);

      if (DB::getSession ()->isTeacher ()) {
        $lehrer = DB::getSession ()->getTeacherObject()->getKuerzel();

        $classes = stundenplandata::getCurrentStundenplan()->getAllGradesForTeacher($lehrer);

        $lnws = Leistungsnachweis::getByClass($classes, $datum, $datum);
        $termine = Klassentermin::getByClass($classes, $datum, $datum);
        $lehrertermine = Lehrertermin::getAll($datum,$datum);


      } else {
        $grades = grade::getMyGradesFromStundenplan();

        $lnws = Leistungsnachweis::getByClass($grades, $datum, $datum);
        $termine = Klassentermin::getByClass($grades, $datum, $datum);

      }

      $hasLNW = false;

      {
        for($i = 0; $i < sizeof($lnws); $i++) {
          if(DB::getSession()->isTeacher() ||  ($lnws[$i]->getArt() != "STEGREIFAUFGABE" && $lnws[$i]->getArt() != "PLNW")) {
            $hasLNW = true;
            $lnwHTML .= "<li><font color=\"" . $lnws[$i]->getEintragFarbe() . "\">" . $lnws[$i]->getKlasse() . ": " . $lnws[$i]->getArtLangtext() . " - " . $lnws[$i]->getFach() . " - " . $lnws[$i]->getLehrer() . "</font></li>";
          }
        }
      }

      if($hasLNW) {
        $lnwHTML = "<b>Leistungsnachweise</b><ul>" . $lnwHTML . "</ul>";
      }
      else {
        $lnwHTML = "<b>Keine Leistungsnachweise</b>";
      }


      $hasTermine = false;
      $termineHTML2 = "";

      {
        for($i = 0; $i < sizeof($termine); $i++) {
          $hasTermine = true;
          $termineHTML2 .= "<li><font color=\"green\">" . $termine[$i]->getTitle() . " - " . $termine[$i]->getLehrer() . "<br /><small>" . implode(", ",$termine[$i]->getKlassen()) . "</small></font></li>";
        }
      }

      if($hasTermine) {
        $termineHTML2 = "<b>Klassentermine</b><ul>" . $termineHTML2 . "</ul>";
      }
      else {
        $termineHTML2 = "<br /><b>Keine Klassentermine</b>";
      }


      $lehrerTermineHTML = "";
      if($this->isActive("lehrerkalender")) {
        $hasTermine = false;


        {
          for($i = 0; $i < sizeof($lehrertermine); $i++) {
            $hasTermine = true;
            $lehrerTermineHTML .= "<li>" . $lehrertermine[$i]->getTitle() . "</small></li>";
          }
        }

        if($hasTermine) {
          $lehrerTermineHTML = "<b>Lehrertermine</b><ul>" . $lehrerTermineHTML . "</ul>";
        }
        else {
          $lehrerTermineHTML = "<br /><b>Keine Lehrertermine</b>";
        }
      }

      $schulkalenderHTML = "";
      if($this->isActive("schulkalender")) {
        $hasTermine = false;


        {
          for($i = 0; $i < sizeof($schultermine); $i++) {
            $hasTermine = true;
            $schulkalenderHTML .= "<li>" . $schultermine[$i]->getTitle() . "</small></li>";
          }
        }

        if($hasTermine) {
          $schulkalenderHTML = "<b>Schulkalender</b><ul>" . $schulkalenderHTML . "</ul>";
        }
        else {
          $schulkalenderHTML = "<br /><b>Keine Schultermine</b>";
        }
      }



    }

    $vplanCollapse = (($this->mySettings['aufeinenblickShowVplan'] == 0) ? (" collapsed-box") : (""));
    $calendarCollapse = (($this->mySettings['aufeinenblickShowCalendar'] == 0) ? (" collapsed-box") : (""));
    $stundenplanCollapse = (($this->mySettings['aufeinenblickShowStundenplan'] == 0) ? (" collapsed-box") : (""));

    $faVplanIcon = (($this->mySettings['aufeinenblickShowVplan'] == 0) ? ("fa-plus") : ("fa-minus"));
    $faCalendarIcon = (($this->mySettings['aufeinenblickShowCalendar'] == 0) ? ("fa-plus") : ("fa-minus"));
    $faStundenplanIcon = (($this->mySettings['aufeinenblickShowStundenplan'] == 0) ? ("fa-plus") : ("fa-minus"));


    eval ( "\$html = \"" . DB::getTPL ()->get ( "aufeinenblick/heutemorgen" ) . "\";" );

    return $html;
  }

  public static function hasSettings() {
    return false;
  }

  public static function getSiteDisplayName() {
    return "\"Auf einen Blick\"";
  }

  public static function getSettingsDescription() {
    return array(


    );
  }

  public static function getUserGroups() {
    return array();
  }

  public static function siteIsAlwaysActive() {
    return true;
  }

  public static function hasAdmin() {
    return false;
  }

  public static function getAdminGroup() {
  	return AUFEINENBLICK_ADMINGROUP;
  }

  public static function displayAdministration($selfURL) {
    return "";
  }

  public static function getAdminMenuGroup() {
  	return 'Seiteneinstellungen';
  }

  public static function getAdminMenuGroupIcon() {
  	return 'fa fa-file';
  }
}

?>
