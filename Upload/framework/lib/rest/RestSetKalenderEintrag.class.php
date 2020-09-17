<?php

class RestSetKalenderEintrag extends AbstractRest {
	protected $statusCode = 200;

	public function execute($input, $request) {


		if ( !$this->user || !$this->user->getUserID() ) {
			return [
				'error' => true,
				'msg' => 'Fehlende User ID'
			];
		}

		$row = [];

		if ( $input['data'] ) {
			$row = $input['data'];
		} else {
			return [
				'error' => true,
				'msg' => 'Fehlende Daten'
			];
		}

		if ( !intval($row['calenderID']) ) {
			return [
				'error' => true,
				'msg' => 'Fehlende Kalender ID'
			];
		}

		$kalender_acl_db = DB::getDB()->query_first("SELECT a.kalenderAcl FROM kalender_api as a 
		WHERE a.kalenderID = ".intval($row['calenderID']));

		if ($kalender_acl_db['kalenderAcl']) {
			$acl = $this->getAclByID($kalender_acl_db['kalenderAcl'], true);
		} else {
			$acl = $this->getAcl();
		}

		if ($acl['rights']['write'] != 1) {
			return [
				'error' => true,
				'msg' => 'Keine Schreibrechte!'
			];
		}

		

		
		if ( !$row['start'] || !$row['end'] ) {
			return [
				'error' => true,
				'msg' => 'Fehlende Kalender Zeiten'
			];
		}
		if ( !$row['title'] ) {
			return [
				'error' => true,
				'msg' => 'Fehlende Kalender Title'
			];
		}
		

		$now = new DateTime();
		$now = $now->format('Y-m-d H:i:s');

		if ( $row['id'] ) {

			$dbRow = DB::getDB()->query_first("SELECT eintragID FROM kalender_api_eintrag WHERE eintragID = " . intval($row['id']) . "");

			if ( $dbRow['eintragID'] ) {


				DB::getDB()->query("UPDATE kalender_api_eintrag SET
					kalenderID = ".intval($row['calenderID']).",
					eintragTitel = '".DB::getDB()->encodeString($row['title'])."',
					eintragDatumStart = '".DB::getDB()->escapeString($row['start'])."',
					eintragDatumEnde = '".DB::getDB()->escapeString($row['end'])."',
					eintragOrt = '".DB::getDB()->encodeString($row['place'])."',
					eintragKommentar = '".DB::getDB()->encodeString(nl2br($row['comment']))."',
					eintragModifiedTime = '".$now."'
					WHERE eintragID = " . intval($row['id']) . ";");
		
			} else {
				return [
					'error' => true,
					'msg' => 'Fehlende Kalender Eintrag'
				];
			}

								
		} else {

			DB::getDB()->query("INSERT INTO kalender_api_eintrag (
				kalenderID,
				eintragTitel,
				eintragDatumStart,
				eintragDatumEnde,
				eintragOrt,
				eintragKommentar,
				eintragUser,
				eintragCreatedTime
				) values (
				".intval($row['calenderID']).",
				'".DB::getDB()->encodeString($row['title'])."',
				'".DB::getDB()->escapeString($row['start'])."',
				'".DB::getDB()->escapeString($row['end'])."',
				'".DB::getDB()->encodeString($row['place'])."',
				'".DB::getDB()->encodeString(nl2br($row['comment']))."',
				".$this->user->getUserID().",
				'".$now."'
			);");

		}

		


		return [
			'error' => false,
			'done' => true
		];

		exit;
	}

	public function getAllowedMethod() {
		return 'POST';
	}

	protected function malformedRequest() {
		$this->statusCode = 400;
	}

	/**
	 * Überprüft, ob ein Modul eine System Authentifizierung benötigt. (z.B. zum Abfragen aller Schülerdaten)
	 * @return boolean
	 */
	public function needsSystemAuth() {
		return false;
	}

	public function needsUserAuth() {
		return true;
	}

	public function aclModuleName() {
		return 'apiKalender';
	}

}	

?>