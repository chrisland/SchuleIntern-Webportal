<?php

class MsgInbox extends AbstractPage {
	public function __construct() {
		parent::__construct(array("Nachrichten"));
		
		$this->checkLogin();
	}
	
	public function execute() {
    
    $userID = DB::getSession()->getUserID();


		MessageSendRights::init();

		$fachschaftenAllowed = MessageSendRights::isFachschaftenAllowed();
		$canRequestReadConfirmation = MessageSendRights::canRequestReadingConfirmation();
		$canAskQuestions = MessageSendRights::canAskQuestions();

		$selectOptionsFachschaften = $this->getFachschaften($fachschaftenAllowed);
		$selectOptionsKlassenteams = $this->getAllowedKlassenteams(true);
		$selectOptionsKlassenleitung = $this->getAllowedKlassenleitungen(true);
		$selectOptionsSchueler = $this->getAllowedPupils(true);
		$selectOptionsSchuelerOwnUnterricht = $this->getSchuelerOwnUnterricht(true);
		$selectOptionsSchuelerKlassen = $this->getSchuelerKlassen();
	
		if( sizeof(MessageSendRights::getAllowedParents()) > 0 ) {
			$selectOptionsElternSingel = $this->getElternSingel();
		}

		if(MessageSendRights::isAllUnterrichtAllowed()) {
			$selectOptionsSchuelerAllUnterricht = $this->getSchuelerAllUnterricht(true);
		}

		if(MessageSendRights::isOwnUnterrichtAllowed()) {
			$selectOptionsElternOwnUnterricht = $this->getElternOwnUnterricht();
		}
		
		if(MessageSendRights::isAllUnterrichtAllowed()) {
			$selectOptionsElternAllUnterricht = $this->getElternAllUnterricht();
		}

    eval("DB::getTPL()->out(\"" . DB::getTPL()->get("msg/inbox") . "\");");
    PAGE::kill(true);
	}


	private function getElternAllUnterricht () {

		/*
		$unterrichte = [];
		
		//$unterrichte = SchuelerUnterricht::searchInBezeichnung($_REQUEST['term']);
						
		$responseData = [];
		
		for($i = 0; $i < sizeof($unterrichte); $i++) {
				
			$recipient = new ParentsOfPupilsOfClassRecipient($unterrichte[$i]);
			
			$responseData[] = [
				'id' => $recipient->getSaveString(),
				'text' => $recipient->getDisplayName(),
				'disabled' =>  false
			];
		}
		
		return json_encode($responseData);
		*/

		return json_encode( array() );

	}

	private function getElternOwnUnterricht() {

		$unterrichte = [];

		if(DB::getSession()->isTeacher()) {
			$unterrichte = SchuelerUnterricht::getUnterrichtForLehrer(DB::getSession()->getTeacherObject());
		}
		
		if(DB::getSession()->isPupil()) {
			$unterrichte = SchuelerUnterricht::getUnterrichtForSchueler(DB::getSession()->getPupilObject());
		}
		
		if(DB::getSession()->isEltern()) {
			$schueler = DB::getSession()->getElternObject()->getMySchueler();
			for($s = 0; $s < sizeof($schueler); $s++) {
				$unterrichts = SchuelerUnterricht::getUnterrichtForSchueler($schueler[$i]);
				$unterrichte = array_merge($unterrichte, $unterrichts);
			}
		}

		$responseData = [];

		for($i = 0; $i < sizeof($unterrichte); $i++) {
	
			$recipient = new ParentsOfPupilsOfClassRecipient($unterrichte[$i]);
			
			$responseData[] = [
				'id' => $recipient->getSaveString(),
				'text' => $recipient->getDisplayName(),
				'disabled' =>  false
			];

		}
		
		return json_encode($responseData);
		
	}

	private function getElternSingel() {

		$parentsRecipients = MessageSendRights::getAllowedParents();
		        
		$responseData = [];
		
		for($i = 0; $i < sizeof($parentsRecipients); $i++) {
						
			$responseData[] = [
				'id' => $parentsRecipients[$i]->getSaveString(),
				'text' => $parentsRecipients[$i]->getDisplayName(),
				'disabled' =>  !$parentsRecipients[$i]->isAvailible()
			];
	
		}
		
		
		return json_encode($responseData);
		
	}

	private function getSchuelerKlassen() {

		$pupilRecipients = MessageSendRights::getAllowedPupilGrades();
		
		$selectIDs = [];
		
		for($i = 0; $i < sizeof($pupilRecipients); $i++) {
			
			$ks = $pupilRecipients[$i]->getKlasse()->getKlassenstufe();
			//if($ks == '') $ks = 'Andere Klassen';
			//else $ks = $ks . ". Klassen";
			
			//$selectIDs[$ks][] = array(
			$selectIDs[] = array(
				"id" => $pupilRecipients[$i]->getSaveString(),
				"text" => $pupilRecipients[$i]->getKlasse()->getKlassenName()
			);

		}

		// echo "<pre>";
		// print_r($selectIDs );
		// echo "</pre>";

		return json_encode($selectIDs);

	}

	private function getSchuelerAllUnterricht() {
		

		//$unterrichte = SchuelerUnterricht::getAll();

		// $daten = DB::getDB()->query("SELECT * FROM unterricht JOIN faecher ON unterrichtFachID=fachID ORDER BY fachKurzForm");
		
		// $us = [];
		
		// while($u = DB::getDB()->fetch_array($daten)) {
		// 	$us[] = new SchuelerUnterricht($u);

		// // 	echo "<pre>";
		// // print_r($u);
		// // echo "</pre>";

		// }
		
		
		// echo "<pre>";
		// print_r($us);
		// echo "</pre>";


		/*
		//$unterrichte = [];
		$unterrichte = SchuelerUnterricht::getAll();

		echo "<pre>";
		print_r($unterrichte);
		echo "</pre>";
		//exit;


		$responseData = [
				'results' => []
		];
		
		for($i = 0; $i < sizeof($unterrichte); $i++) {
			$recipient = new PupilsOfClassRecipient($unterrichte[$i]);
			
			$responseData['results'][] = [
				'id' => $recipient->getSaveString(),
				'text' => $recipient->getDisplayName(),
				'disabled' =>  false
			];
		}
		
		return json_encode($responseData);
		*/
		
		return json_encode( array() );
	}


	private function getSchuelerOwnUnterricht() {

		$unterrichte = [];
		
		if(MessageSendRights::isOwnUnterrichtAllowed()) {
				
				if(DB::getSession()->isTeacher()) {
						$unterrichte = SchuelerUnterricht::getUnterrichtForLehrer(DB::getSession()->getTeacherObject());
				}
				
				if(DB::getSession()->isPupil()) {
						$unterrichte = SchuelerUnterricht::getUnterrichtForSchueler(DB::getSession()->getPupilObject());
				}
				
				if(DB::getSession()->isEltern()) {
						$schueler = DB::getSession()->getElternObject()->getMySchueler();
						for($s = 0; $s < sizeof($schueler); $s++) {
								$unterrichts = SchuelerUnterricht::getUnterrichtForSchueler($schueler[$i]);
								$unterrichte = array_merge($unterrichte, $unterrichts);
						}
				}
		}
		
		$results = [];
		
		for($i = 0; $i < sizeof($unterrichte); $i++) {

			$recipient = new PupilsOfClassRecipient($unterrichte[$i]);
			$results[] = [
				'id' => $recipient->getSaveString(),
				'text' => $recipient->getDisplayName(),
				'disabled' =>  false
			];
		}

		return json_encode($results);

	}


	private function getAllowedPupils ($allowed) {

		$result = [];
		if($allowed) {
			$list = MessageSendRights::getAllowedPupils();
			for($i = 0; $i < sizeof($list); $i++) {
				array_push( $result, [ 'id' => $list[$i]->getSaveString(), 'text' => $list[$i]->getDisplayName() ] );
			}
		}
		return json_encode($result);

	}


	private function getAllowedKlassenleitungen ($allowed) {

		$result = [];
		if($allowed) {
			$list = MessageSendRights::getAllowedKlassenleitungen();
			for($i = 0; $i < sizeof($list); $i++) {
				array_push( $result, [ 'id' => $list[$i]->getSaveString(), 'text' => $list[$i]->getDisplayName() ] );
			}
		}
		return json_encode($result);

	}


	private function getAllowedKlassenteams ($allowed) {

		$result = [];
		if($allowed) {
			$list = MessageSendRights::getAllowedKlassenteams();
			for($i = 0; $i < sizeof($list); $i++) {
				array_push( $result, [ 'id' => $list[$i]->getSaveString(), 'text' => $list[$i]->getDisplayName() ] );
			}
		}
		return json_encode($result);

	}

	private function getFachschaften ($allowed) {

		$result = [];
		if($allowed) {
			$list = FachschaftRecipient::getAllInstances();
			for($i = 0; $i < sizeof($list); $i++) {
				array_push( $result, [ 'id' => $list[$i]->getSaveString(), 'text' => $list[$i]->getDisplayName() ] );
			}
		}
		return json_encode($result);

	}




	
	public static function getSettingsDescription() {
		$settings = [];
		
		
		return $settings;
	}
	
	public static function getSiteDisplayName() {
		return "Msg - Posteingang";
	}
	
	public static function hasSettings() {
		return false;
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
	
	public static function getAdminGroup() {
		return "NONE";
	}
	
	public static function displayAdministration($selfURL) {
		
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fa-info-circle';
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fa-info-circle';
	}
	
	public static function getAdminMenuGroup() {
		return 'Schulinformationen';
	}
}


?>