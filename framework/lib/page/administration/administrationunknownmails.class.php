<?php

class administrationunknownmails extends AbstractPage {

	private $info;
	
	public function __construct() {
		$this->needLicense = false;
		
		parent::__construct(array("Administration", "Nicht erkannte Mails / Mails an " . DB::getGlobalSettings()->smtpSettings['sender']));
		
		$this->checkLogin();
		
		new errorPage();
		
	}

	public function execute() {}
	
	public static function displayAdministration($selfURL) {
		
		if($_GET['action'] == "deleteAllMail") {
			DB::getDB()->query("DELETE FROM unknown_mails");
		}
		
		
		$mails = DB::getDB()->query("SELECT * FROM unknown_mails");
		
		$anzahl = 0;
		while($mail = DB::getDB()->fetch_array($mails)) {
			if($_GET['action'] == "deleteMail" && $_GET['mailID'] == $mail['mailID']) {
				DB::getDB()->query("DELETE FROM unknown_mails WHERE mailID='" . $mail['mailID'] . "'");
			}
			else {
				$anzahl++;
				$mail['mailSubject'] = (mb_decode_mimeheader ($mail['mailSubject']));
				$mail['mailText'] = (mb_decode_mimeheader ($mail['mailText']));
				
				
				$mail['mailSender'] = (mb_decode_mimeheader ($mail['mailSender']));
				
				
				$mail['mailSender'] = @htmlspecialchars($mail['mailSender']);
				
				$mail['mailText'] = @strip_tags($mail['mailText']);
				$mail['mailSubject'] = @htmlspecialchars($mail['mailSubject']);
				
				
				// MailText bearbeiten
				
				$lines = explode("\n",str_replace("\r","",$mail['mailText']));
				
				for($i = 0; $i < sizeof($lines); $i++) {
					if(strlen($lines[$i]) > 100) {
						$line = str_split($lines[$i]);
						$newLine = "";
						$count = 0;
						for($c = 0; $c < sizeof($line); $c++) {
							$count++;
							if($count == 100) {
								$newLine .= "\n";
								$count = 0;
							}
							$newLine .= $line[$c];
						}
						
						$lines[$i] = $newLine;
					}
				}
				
				$mail['mailText'] = implode("\n",$lines);
						
				$mail['mailText'] = nl2br($mail['mailText']);
						
				
				
				eval("\$mailHTML .= \"" . DB::getTPL()->get("administration/unknownmails/mail_bit") . "\";");
			}
		}
		
		
		$html = "";
		
		eval("\$html =  \"" . DB::getTPL()->get("administration/unknownmails/index") . "\";");
		
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
		return 'Unbekannte E-Mails';
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
		return true;
	}
	
	public static function getAdminMenuGroup() {
		return "E-Mailverwaltung";
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fa-envelope';
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fa-envelope';
	}
}


?>