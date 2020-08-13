<?php


use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;


class NotenverwaltungZeugnisse extends AbstractPage {


  public function __construct() {

    if(!DB::getGlobalSettings()->hasNotenverwaltung) {
      die("Notenverwaltung nicht lizenziert.");
    }

    parent::__construct(['Notenverwaltung', 'Zeugnisse'],false,false,true);

    if(!DB::getSession()->isTeacher()) {
      new errorPage();
    }

  }

  public function execute() {
      switch($_REQUEST['action']) {
          case 'zwischenbericht':
              $this->zwischenbericht();
          break;
          
          case "printZwischenbericht":
              $this->printZwischenbericht();
          break;

          case 'addZeugnis':
              $this->addZeugnis();
          break;

          case 'deleteZeugnis':
              $zeugnis = NoteZeugnis::getByID($_REQUEST['zeugnisID']);
              if($zeugnis != null) {
                  $zeugnis->delete();
              }

              header("Location: index.php?page=NotenverwaltungZeugnisse");
              exit(0);
          break;

          case 'printZeugnis':
              $this->printZeugnis();
          break;

          default:
              $this->index();
          break;
      }
  }

  private function printZeugnis() {
      $zeugnis = NoteZeugnis::getByID($_REQUEST['zeugnisID']);

        if($zeugnis == null) {
            new errorPage('Ungültige Zeugnis Angabe');
        }

        switch($_REQUEST['mode']) {
            default:
                $this->zeugnisDruckIndex($zeugnis);
            break;

            case 'viewKlasse':
                $this->zeugnisDruckViewKlasse($zeugnis);
            break;

            case 'generateZeugnis':
                $this->generateSingleZeugnis($zeugnis);
            break;

            case 'generateForKlasse':
                $this->generateZeugnisForKlasse($zeugnis);
            break;

            case 'getAllInZip':
                $this->getAllIinZip($zeugnis);
            break;
        }
  }


  /**
   *
   * @param NoteZeugnis $zeugnis
   */
  private function generateZeugnisForKlasse($zeugnis) {
      $zeugnisKlassen = $zeugnis->getZeugnisKlassen();

      $noteZeugnisKlasse = null;

      for($i = 0; $i < sizeof($zeugnisKlassen); $i++) {
          if($zeugnisKlassen[$i]->getKlasse()->getKlassenName() == $_REQUEST['klasse']) {
              $noteZeugnisKlasse = $zeugnisKlassen[$i];
              break;
          }
      }

      if($noteZeugnisKlasse == null) {
          die("FAIL");
      }

      $schueler = $noteZeugnisKlasse->getKlasse()->getSchueler();

      for($i = 0; $i < sizeof($schueler); $i++) {
          $this->generateZeugnis($zeugnis, $schueler[$i], $noteZeugnisKlasse);
      }

      // if($schueler == null) die("FAIL SCHÜLER UNBEKANNT.");

      // $this->generateZeugnis($zeugnis, $schueler, $noteZeugnisKlasse);

      header("Location: index.php?page=NotenverwaltungZeugnisse&action=printZeugnis&zeugnisID=" . $zeugnis->getID() . "&mode=viewKlasse&klasse=" . $noteZeugnisKlasse->getKlasse()->getKlassenName());
  }

  private function getAllIinZip($zeugnis) {
      $zeugnisKlassen = $zeugnis->getZeugnisKlassen();

      $noteZeugnisKlasse = null;

      for($i = 0; $i < sizeof($zeugnisKlassen); $i++) {
          if($zeugnisKlassen[$i]->getKlasse()->getKlassenName() == $_REQUEST['klasse']) {
              $noteZeugnisKlasse = $zeugnisKlassen[$i];
              break;
          }
      }

      if($noteZeugnisKlasse == null) {
          die("FAIL");
      }

      $klasse = $noteZeugnisKlasse->getKlasse();

      // Zeugnisse der Klasse laden
      $schuelerAsvIDs = [];
      $schueler = klasse::getByName($noteZeugnisKlasse->getKlasse()->getKlassenName())->getSchueler();

      for($i = 0; $i < sizeof($schueler); $i++) $schuelerAsvIDs[] = $schueler[$i]->getAsvID();

      // Debugger::debugObject($schuelerAsvIDs,1);


      $exemplareSQL = DB::getDB()->query("SELECT * FROM noten_zeugnis_exemplar WHERE zeugnisID='" . $zeugnis->getID() . "' AND schuelerAsvID IN('" . implode("','" , $schuelerAsvIDs) . "')");
      $exemplare = [];
      while($e = DB::getDB()->fetch_array($exemplareSQL)) $exemplare[] = $e;


      $zip = new ZipArchive();
      $filename = "../data/temp/pdf_zeugnisse_export" . md5(rand()) . ".zip";

      if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
          die("cannot open --> $filename\n");
      }


      $commandFilePrintAll = "@echo off
echo Startet den Druck aller Zeugnisse. Es wird auf den Standarddrucker in Microsoft Word gedruckt.
pause\r\n";


      for($i = 0; $i < sizeof($schueler); $i++) {

          for($e = 0; $e < sizeof($exemplare); $e++) {
              if($exemplare[$e]['schuelerAsvID'] == $schueler[$i]->getAsvID()) {
                  $upload = FileUpload::getByID($exemplare[$e]['uploadID']);
                  if($upload != null) {
                      $zip->addFile($upload->getFilePath(), $upload->getFileName());
                        $commandFilePrintAll .= "echo Drucke \"" . $upload->getFileName() . "\r\n";
                      $commandFilePrintAll .= "start /WAIT winword \"" . utf8_decode($upload->getFileName()) . ".docx\" /mFilePrintDefault /mFileCloseOrExit\r\n";
                  }
              }
          }


      }

      $commandFilePrintAll .= "@echo Druck abgeschlossen.";

      // $upload = FileUpload::uploadTextFileContents("printzeugnisse_tamp", $commandFilePrintAll);

      // $zip->addFile($upload['uploadobject']->getFilePath(), "# Alle Zeugnisse drucken.bat");

      $zip->close();

      // Send File

      $file = $filename;

      header('Content-Description: File Transfer');
      header('Content-Type: application/zip');
      header('Content-Disposition: attachment; filename='.basename("Klasse" . $klasse->getKlassenName() . " - Zeugnisse.zip"));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
      header('Pragma: public');
      header('Content-Length: ' . filesize($file));
      ob_clean();
      flush();
      readfile($file);

      // unlink($file);
      exit(0);


  }

  /**
   *
   * @param NoteZeugnis $zeugnis
   */
  private function generateSingleZeugnis($zeugnis) {
      $zeugnisKlassen = $zeugnis->getZeugnisKlassen();

      $noteZeugnisKlasse = null;

      for($i = 0; $i < sizeof($zeugnisKlassen); $i++) {
          if($zeugnisKlassen[$i]->getKlasse()->getKlassenName() == $_REQUEST['klasse']) {
              $noteZeugnisKlasse = $zeugnisKlassen[$i];
              break;
          }
      }

      if($noteZeugnisKlasse == null) {
          die("FAIL");
      }

      $schueler = schueler::getByAsvID($_REQUEST['schuelerAsvID']);

      if($schueler == null) die("FAIL SCHÜLER UNBEKANNT.");

      $this->generateZeugnis($zeugnis, $schueler, $noteZeugnisKlasse);

      header("Location: index.php?page=NotenverwaltungZeugnisse&action=printZeugnis&zeugnisID=" . $zeugnis->getID() . "&mode=viewKlasse&klasse=" . $noteZeugnisKlasse->getKlasse()->getKlassenName());
  }


  /**
   *
   * @param NoteZeugnis $zeugnis
   */
  private function zeugnisDruckViewKlasse($zeugnis) {
      $zeugnisKlassen = $zeugnis->getZeugnisKlassen();

      $noteZeugnisKlasse = null;

      for($i = 0; $i < sizeof($zeugnisKlassen); $i++) {
          if($zeugnisKlassen[$i]->getKlasse()->getKlassenName() == $_REQUEST['klasse']) {
              $noteZeugnisKlasse = $zeugnisKlassen[$i];
              break;
          }
      }

      if($noteZeugnisKlasse == null) {
          die("FAIL");
      }

      $klasse = $noteZeugnisKlasse->getKlasse();

      // Zeugnisse der Klasse laden
      $schuelerAsvIDs = [];
      $schueler = klasse::getByName($noteZeugnisKlasse->getKlasse()->getKlassenName())->getSchueler();

      for($i = 0; $i < sizeof($schueler); $i++) $schuelerAsvIDs[] = $schueler[$i]->getAsvID();

      // Debugger::debugObject($schuelerAsvIDs,1);


      $exemplareSQL = DB::getDB()->query("SELECT * FROM noten_zeugnis_exemplar WHERE zeugnisID='" . $zeugnis->getID() . "' AND schuelerAsvID IN('" . implode("','" , $schuelerAsvIDs) . "')");
      $exemplare = [];
      while($e = DB::getDB()->fetch_array($exemplareSQL)) $exemplare[] = $e;


      $schuelerHTML = "";

      for($i = 0; $i < sizeof($schueler); $i++) {
          $schuelerHTML .= "<tr><td>" . $schueler[$i]->getCompleteSchuelerName() . "</td><td>";

          $found = false;

          for($e = 0; $e < sizeof($exemplare); $e++) {
              if($exemplare[$e]['schuelerAsvID'] == $schueler[$i]->getAsvID()) {
                  $upload = FileUpload::getByID($exemplare[$e]['uploadID']);
                  $erzeugt = functions::makeDateFromTimestamp($exemplare[$e]['createdTime']);

                  $schuelerHTML .= "<a href=\"" . $upload->getURLToFile() . "\"><i class=\"fa fa-file-word-o\"></i> Download</a><br />Erzeugt: $erzeugt";
                  $found = true;
              }
          }

          if(!$found) {
              $schuelerHTML .= "<i>Bisher nicht erzeugt</i>";
          }

          $schuelerHTML .= "</td><td><a href=\"index.php?page=NotenverwaltungZeugnisse&action=printZeugnis&zeugnisID=" . $zeugnis->getID() . "&klasse=" . urlencode($klasse->getKlassenName()) . "&mode=generateZeugnis&schuelerAsvID=" . urlencode($schueler[$i]->getAsvID()) . "\"><i class=\"fa fas fa-sync-alt\"></i> Zeugnis generieren</a><br />";


          $schuelerHTML .= "</tr>";
      }

      eval("DB::getTPL()->out(\"" . DB::getTPL()->get("notenverwaltung/zeugnisse/druck/klasse/index") . "\");");


  }

  /**
   *
   * @param NoteZeugnis $zeugnis
   * @param schueler $schueler
   * @param NoteZeugnisKlasse $zeugnisKlasse
   */
  private function generateZeugnis($zeugnis, $schueler, $zeugnisKlasse) {
      $bemerkung = NoteZeugnisBemerkung::getForSchueler($schueler, $zeugnis);

      $text1 = " ";
      $text2 = " ";
      $bestanden = (($schueler->getGeschlecht() == 'w') ? "sie nicht erhalten" : "er nicht erhalten");


      if($bemerkung != null) {
          $text1 = $bemerkung->getText1();
          $text2 = $bemerkung->getText2();

          if($text1 == "") $text1 = " ";
          if($text2 == "") $text2 = " ";

          if($bemerkung->klassenzielErreicht()) {
              $bestanden = (($schueler->getGeschlecht() == 'w') ? "sie erhalten" : "er erhalten");
          }
      }

      $nachname = "";

      if($schueler->getNamensbestandteilVorgestellt() != "") $nachname .= $schueler->getNamensbestandteilVorgestellt() . " ";
      $nachname .= $schueler->getName();
      if($schueler->getNamensbestandteilNachgestellt() != "") $nachname .= " " . $schueler->getNamensbestandteilNachgestellt();

      $schuelerName = $schueler->getVornamen() . " " . $nachname;

      include_once("../framework/lib/phpword/vendor/autoload.php");

      // die(getcwd());

      $templateProcessor = new TemplateProcessor('../framework/vorlagen/notenverwaltung/zeugnisse/zeugnis2.docx');

      $templateProcessor->setValue("{BEMERKUNG2}", $text2);

      $templateProcessor->setValue("{KLASSE}", $schueler->getKlasse());

      $templateProcessor->setValue("{SCHUELERNAME}", $schuelerName);


      // {DENSCHUELERSCHUELERIN}
      if($schueler->getGeschlecht() == "w") {
          $templateProcessor->setValue("{DENSCHUELERSCHUELERIN}", "die Schülerin");
      }
      else {
          $templateProcessor->setValue("{DENSCHUELERSCHUELERIN}", "den Schüler");
      }



      $templateProcessor->setValue("{GEBURTSDATUM}", $schueler->getGeburtstagAsNaturalDate());
      $templateProcessor->setValue("{GEBURTSORT}", $schueler->getGeburtsort());
      $templateProcessor->setValue("{SCHULJAHR}", DB::getSettings()->getValue("general-schuljahr"));

      if($schueler->getAusbildungsrichtung() != "GY") {

          $ausbildungsrichtung = "";

          if($schueler->getAusbildungsrichtung() == "GY_WSG-W_8") {
              $ausbildungsrichtung = "Wirtschaftswissenschaftlichen ";
          }

          if($schueler->getAusbildungsrichtung() == "GY_SG_8") {
              $ausbildungsrichtung = "Sprachlichen ";
          }

          $templateProcessor->setValue("{AUSBILDUNGSRICHTUNG}", $ausbildungsrichtung);

      }
      else {
          $templateProcessor->setValue("{AUSBILDUNGSRICHTUNG}", "");
      }

      // $templateProcessor->setValue("{AUSBILDUNGSRICHTUNG}", $zeugnisKlasse->getKlasse()->getAusbildungsrichtungen());

      $templateProcessor->setValue("{BEMERKUNG1}", $text1);

      $templateProcessor->setValue("{VOR}", $bestanden);

      $templateProcessor->setValue("{DATUM}", DateFunctions::getNaturalDateFromMySQLDate($zeugnisKlasse->getDatumAsSQLDate()));
      $templateProcessor->setValue("{USL}", $zeugnisKlasse->getSchulleitung()->getZeugnisUnterschrift());
      $templateProcessor->setValue("{UKL}", $zeugnisKlasse->getKlassenleitung()->getZeugnisUnterschrift());

      $zeugnisNoten = NoteZeugnisNote::getZeugnisNotenForSchueler($zeugnis, $schueler);

      $noten = [];

      for($n = 1; $n <= 21; $n++) {
          $noten['{N' . $n . "}"] = '--------------';
      }

      // Debugger::debugObject($noten,1);


      $lfs = "";
      $grfs = "";
      $spfs = "";
      $efs = "";
      $ffs = "";
      $ra = "--";


      for($z = 0; $z < sizeof($zeugnisNoten); $z++) {
          $fach = $zeugnisNoten[$z]->getFach()->getKurzform();

          switch($fach) {
              case 'Ev':
                  $ra = "ev.";
                  $noten["{N1}"] = $zeugnisNoten[$z]->getWertText();
              break;



              case 'K':
                  $ra = "r.-k.";
                  $noten["{N1}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'D':
                  $noten["{N3}"] = $zeugnisNoten[$z]->getWertText();
              break;

              case 'B':
                  $noten["{N12}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'C':
                  $noten["{N11}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Ph':
                  $noten["{N10}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'G':
                  $noten["{N14}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Geo':
                  $noten["{N15}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'WIn':
                  $noten["{N21}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Inf':
                  $noten["{N9}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Ku':
                  $noten["{N18}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'M':
                  $noten["{N8}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Mu':
                  $noten["{N19}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'NuT':
                  $noten["{N13}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Sp':
                  $noten["{N5}"] = $zeugnisNoten[$z]->getWertText();
                  $spfs = $this->getFremdspracheNummer("Spanisch", $schueler);
                  break;

              case 'Sk':
                  $noten["{N17}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Sm':
                  $noten["{N20}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'Sw':
                  $noten["{N20}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'E':
                  $noten["{N6}"] = $zeugnisNoten[$z]->getWertText();
                  $efs = $this->getFremdspracheNummer("Englisch", $schueler);
                  break;


              case 'L':
                  $noten["{N4}"] = $zeugnisNoten[$z]->getWertText();
                  $lfs = $this->getFremdspracheNummer("Latein", $schueler);
                  break;

              case 'F':
                  $noten["{N7}"] = $zeugnisNoten[$z]->getWertText();
                  $ffs = $this->getFremdspracheNummer("Französisch", $schueler);
                  break;

              case 'Eth':
                  $noten["{N2}"] = $zeugnisNoten[$z]->getWertText();
                  break;

              case 'WR':
                  $noten["{N16}"] = $zeugnisNoten[$z]->getWertText();
                  break;

          }
      }



      foreach ($noten as $k => $v) {
          $templateProcessor->setValue($k, $v);
      }



      $templateProcessor->setValue("{LFS}", $lfs);
      $templateProcessor->setValue("{GRFS}", $grfs);
      $templateProcessor->setValue("{EFS}", $efs);
      $templateProcessor->setValue("{FFS}", $ffs);
      $templateProcessor->setValue("{RA}", $ra);
      $templateProcessor->setValue("{SPFS}", $spfs);

      $fileUpload = FileUpload::generateUploadID($zeugnis->getArt() . " - " . $schueler->getCompleteSchuelerName() . ".docx", "docx", true, false);


      $templateProcessor->saveAs($fileUpload['uploadobject']->getFilePath());

      DB::getDB()->query("INSERT INTO noten_zeugnis_exemplar (zeugnisID, schuelerAsvID, uploadID, createdTime) values('" . $zeugnis->getID() . "','" . $schueler->getAsvID() . "','" . $fileUpload['uploadobject']->getID() . "',UNIX_TIMESTAMP())


        ON DUPLICATE KEY UPDATE uploadID='" . $fileUpload['uploadobject']->getID() . "', createdTime=UNIX_TIMESTAMP()");
  }

  private function getFremdspracheNummer($fach, $schueler) {
      $data = DB::getDB()->query_first("SELECT * FROM schueler_fremdsprache WHERE schuelerAsvID='" . $schueler->getAsvID() . "' AND spracheFach='" . $fach . "'");
      return $data['spracheSortierung'] . ". Fremdsprache";
  }

  /**
   *
   * @param NoteZeugnis $zeugnis
   */
  private function zeugnisDruckIndex($zeugnis) {
      $zeugnisKlassen = $zeugnis->getZeugnisKlassen();

      $listKlassen = "";

      for($i = 0; $i < sizeof($zeugnisKlassen); $i++) {

          $listKlassen .= "<a href=\"index.php?page=NotenverwaltungZeugnisse&action=printZeugnis&zeugnisID=" . $zeugnis->getID() . "&mode=viewKlasse&klasse=" . urlencode($zeugnisKlassen[$i]->getKlasse()->getKlassenName()) . "\"><i class=\"fa fa-group\"></i> Klasse " . $zeugnisKlassen[$i]->getKlasse()->getKlassenName() . "</a><br />";
      }

      eval("DB::getTPL()->out(\"" . DB::getTPL()->get("notenverwaltung/zeugnisse/druck/index/index") . "\");");
  }

  private function addZeugnis() {
      $typ = $_POST['zeugnisTyp'];
      $name = $_POST['zeugnisName'];

      DB::getDB()->query("INSERT INTO noten_zeugnisse (zeugnisName, zeugnisArt) values('" . DB::getDB()->escapeString($name) . "','" . DB::getDB()->escapeString($typ) . "')");
      $newID = DB::getDB()->insert_id();

      $klassen = klasse::getAllKlassen();

      for($i = 0; $i < sizeof($klassen); $i++) {
          if($_POST[$klassen[$i]->getKlassenName() . "_checked"] > 0) {
              $datum = DateFunctions::getMySQLDateFromNaturalDate($_POST[$klassen[$i]->getKlassenName() . "_datum"]);
              $notenschluss = DateFunctions::getMySQLDateFromNaturalDate($_POST[$klassen[$i]->getKlassenName() . "_notenSchluss"]);

              $kl = $_POST[$klassen[$i]->getKlassenName() . "_kl"];
              $sl = $_POST[$klassen[$i]->getKlassenName() . "_sl"];

              $klGez = $_POST[$klassen[$i]->getKlassenName() . "_kl_gez"];
              $slGez = $_POST[$klassen[$i]->getKlassenName() . "_sl_gez"];

              DB::getDB()->query("INSERT INTO noten_zeugnisse_klassen
                    (
                        zeugnisID,
                        zeugnisKlasse,
                        zeugnisDatum,
                        zeugnisNotenschluss,
                        zeugnisUnterschriftKlassenleitungAsvID,
                        zeugnisUnterschriftSchulleitungAsvID,
                        zeugnisUnterschriftKlassenleitungAsvIDGezeichnet,
                        zeugnisUnterschriftSchulleitungAsvIDGezeichnet
                    ) values (
                        '" . $newID . "',
                        '" . DB::getDB()->escapeString($klassen[$i]->getKlassenName()) . "',
                        '" . DB::getDB()->escapeString($datum) . "',
                        '" . DB::getDB()->escapeString($notenschluss) . "',
                        '" . DB::getDB()->escapeString($kl) . "',
                        '" . DB::getDB()->escapeString($sl) . "',
                        '" . DB::getDB()->escapeString($klGez) . "',
                        '" . DB::getDB()->escapeString($slGez) . "'
                    )
                ");
          }
      }

      header("Location: index.php?page=NotenverwaltungZeugnisse");
      exit();
  }

  private function index() {
      $zeugnisse = NoteZeugnis::getAll();

      $zeugnisListe = "";
      for($i = 0; $i < sizeof($zeugnisse); $i++) {
          $zeugnisListe .= "<tr><td>" . $zeugnisse[$i]->getArtName() . "</td><td>";

          $klassen = $zeugnisse[$i]->getZeugnisKlassen();

          for($k = 0; $k < sizeof($klassen); $k++) {
              $zeugnisListe .= "<b>" . $klassen[$k]->getKlasse()->getKlassenName() . "</b> - " . DateFunctions::getNaturalDateFromMySQLDate($klassen[$k]->getDatumAsSQLDate()) . " - Notenschluss: " . DateFunctions::getNaturalDateFromMySQLDate($klassen[$k]->getNotenschulussAsSQLDate()) . "<br />";

              $zeugnisListe .= "KL: " . ($klassen[$k]->getKlassenleitung() != null ? $klassen[$k]->getKlassenleitung()->getDisplayNameMitAmtsbezeichnung() : "n/a") . "<br />";
              $zeugnisListe .= "SL: " . ($klassen[$k]->getSchulleitung() != null ? $klassen[$k]->getSchulleitung()->getDisplayNameMitAmtsbezeichnung() : "n/a") . "<br />";
          }


          $zeugnisListe .= "</td>";
          $zeugnisListe .= "<td>";

          $zeugnisListe .= "<button type=\"buton\" class=\"btn btn-primary\" onclick=\"window.location.href='index.php?page=NotenverwaltungZeugnisse&action=printZeugnis&zeugnisID=" . $zeugnisse[$i]->getID() . "'\"><i class=\"fa fa-print\"></i> Zeugnisse drucken</button><br />";

          $zeugnisListe .= '<button type="button" class="btn btn-danger" onclick="confirmAction(\'Zeugnis wirklich löschen? (WARNUNG: Lösche alle Bermerkungen, Zeugnisnoten etc.)\',\'index.php?page=NotenverwaltungZeugnisse&action=deleteZeugnis&zeugnisID=' . $zeugnisse[$i]->getID() . '\');"><i class="fa fa-trash"></i> Löschen</button>';

          $zeugnisListe . "</td>";




          $zeugnisListe .= "</tr>";
      }



      $klassenHTML = "";

      $klassen = klasse::getAllKlassen();

      $schulleitung = schulinfo::getSchulleitungLehrerObjects();

      $SL = null;
      if(sizeof($schulleitung) > 0) {
          $SL = $schulleitung[0];
      }

      $SLOptions = $this->getTeacherSelectOptions($SL);

      for($i = 0; $i < sizeof($klassen); $i++) {
          $klassenHTML .= "<tr><td><input type=\"checkbox\" class=\"icheck\" name=\"" . $klassen[$i]->getKlassenName() . "_checked\" value=\"1\" checked></td>";

          $klassenHTML .= "<td>" . $klassen[$i]->getKlassenName() . "</td>";

          $klassenHTML .= "<td><input type=\"text\" class=\"dateSelectDatum\" name=\"" . $klassen[$i]->getKlassenName() . "_datum\"></td>";
          $klassenHTML .= "<td><input type=\"text\" class=\"dateSelectDatumNotenschluss\" name=\"" . $klassen[$i]->getKlassenName() . "_notenSchluss\"></td>";

          // Klassenleitung

          $klassenleitung = $klassen[$i]->getKlassenLeitung();

          $KL = null;
          if(sizeof($klassenleitung) > 0) $KL = $klassenleitung[0];


          $klassenHTML .= "<td><select name=\"" . $klassen[$i]->getKlassenName() . "_kl\" class=\"form-control\">" . $this->getTeacherSelectOptions($KL) . "</select>
                <label><input type=\"checkbox\" class=\"icheck\" name=\"" . $klassen[$i]->getKlassenName() . "_kl_gez\" value=\"1\"> Mit gez. Unterschreiben</label>
            </td>";

          // Schulleitung

          $klassenHTML .= "<td><select name=\"" . $klassen[$i]->getKlassenName() . "_sl\" class=\"form-control\">" . $SLOptions . "</select>
                <label><input type=\"checkbox\" class=\"icheck\" name=\"" . $klassen[$i]->getKlassenName() . "_sl_gez\" value=\"1\"> Mit gez. Unterschreiben</label>
            </td>";



          $klassenHTML .= "</tr>";
      }

      eval("DB::getTPL()->out(\"" . DB::getTPL()->get("notenverwaltung/zeugnisse/index") . "\");");
  }

  /**
   *
   * @param lehrer $selectedTeacherObject
   */
  private function getTeacherSelectOptions($selectedTeacherObject = null) {
      $lehrer = lehrer::getAll();

      $html = "";

      for($i = 0; $i < sizeof($lehrer); $i++) {
          $selected = "";
          if($selectedTeacherObject != null) {
              if($selectedTeacherObject->getAsvID() == $lehrer[$i]->getAsvID()) $selected = "selected";
          }
          $html .= "<option value=\"" . $lehrer[$i]->getAsvID() . "\"$selected>" . $lehrer[$i]->getDisplayNameMitAmtsbezeichnung() . "</option>";
      }

      return $html;
  }

  private function zwischenbericht() {
      $checkBoxesKlassen = "";

      $klassen = klasse::getAllKlassen();

      for($i = 0; $i < sizeof($klassen); $i++) {
          $checkBoxesKlassen .= "<label><input type=\"checkbox\" name=\"klasse_" . $klassen[$i]->getKlassenName() . "\" value=\"1\" checked=\"checked\"> " . $klassen[$i]->getKlassenName() . "</label><br />";
      }

      eval("DB::getTPL()->out(\"" . DB::getTPL()->get("notenverwaltung/zeugnisse/zb") . "\");");
      
  }

  private function printZwischenbericht() {
      // Datum:
      $datum = $_REQUEST['datum'];

      $schulleitung = schulinfo::getSchulleitungLehrerObjects();
      if(sizeof($schulleitung) > 0) $schulleitung = $schulleitung[0];
      else new errorPage("Es ist keine Schulleitung in den Schulinformationen definiert.");

      $nameSchulleitung = $schulleitung->getDisplayNameMitAmtsbezeichnung();

      $titel = "<font size=\"12\">" . DB::getSettings()->getValue('schulinfo-name') . "</font><br />";
      if(DB::getSettings()->getValue('schulinfo-name-zusatz') != "") $titel .= "<font size=\"12\"><b>" . DB::getSettings()->getValue('schulinfo-name-zusatz') . "</b></font><br />";
      $titel .= DB::getSettings()->getValue('schulinfo-adresse1') . ", " . DB::getSettings()->getValue('schulinfo-plz') . " " . DB::getSettings()->getValue('schulinfo-ort') . "<br /><br />";

      $schulort = DB::getSettings()->getValue('schulinfo-ort');

      $klassen = klasse::getAllKlassen();

      $print = new PrintNormalPageA4WithoutHeader('Zwischenberichte');

      for($i = 0; $i < sizeof($klassen); $i++) {
          if($_POST['klasse_' . $klassen[$i]->getKlassenName()] > 0) {
              $schueler = $klassen[$i]->getSchueler();

              $klassenleitung = $klassen[$i]->getKlassenLeitung();
              if(sizeof($klassenleitung) > 0) $klassenleitung = $klassenleitung[0]->getDisplayNameMitAmtsbezeichnung();
              else $klassenleitung = "n/a";

              for($s = 0; $s < sizeof($schueler); $s++) {
                  $notenbogen = new Notenbogen($schueler[$s]);

                  $hasNA = $schueler[$s]->getNachteilsausgleich() != null;


                  $tabelle = $notenbogen->getNotentabelleZwischenbericht();

                  $schuelerName = $schueler[$s]->getCompleteSchuelerName();
                  $klasse = $klassen[$i]->getKlassenName();

                  $geburtsdatum = $schueler[$s]->getGeburtstagAsNaturalDate();

                  $absenzen = $notenbogen->getAbsenzen();

                  eval("\$html = \"" . DB::getTPL()->get("notenverwaltung/zeugnisse/druck/zwischenbericht") . "\";");

                  $print->setHTMLContent($html);

                  // if($s == 2) break;

              }

          }
      }

      $print->send();
  }

  public static function hasSettings() {
    return false;
  }

  public static function getSettingsDescription() {
    return [];
  }

  public static function getSiteDisplayName() {
    return 'Notenverwaltung - Startseite';
  }

  public static function siteIsAlwaysActive() {
    return true;
  }

  public static function getAdminGroup() {
    return 'Webportal_Notenverwaltung_Admin';
  }
  
  public static function need2Factor() {
      return TwoFactor::is2FAActive() && TwoFactor::force2FAForNoten();
  }

}


?>
