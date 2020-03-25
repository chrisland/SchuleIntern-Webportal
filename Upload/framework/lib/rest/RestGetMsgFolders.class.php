<?php

class RestGetMsgFolders extends AbstractRest {
  
  protected $statusCode = 200;
	
	public function execute($input, $request) {

		$userID = (int)$request[1];

		if (!$userID) {
			return [
				'error' => true,
				'msg' => 'Fehlende User ID'
			];
		}

		$user = new user(array('userID' => $userID));

		if (!$user) {
			return [
				'error' => true,
				'msg' => 'Fehlender User'
			];
		}
	
		$folders = array(
			'inbox' => MessageFolder::getFolder($user, 'Posteingang')->getJSON(),
			'outbox' => MessageFolder::getFolder($user, 'Gesendete')->getJSON(),
			'archiv' => MessageFolder::getFolder($user, 'Archiv')->getJSON(),
			'rubish' => MessageFolder::getFolder($user, 'Papierkorb')->getJSON()
		);
		$folders = $folders + MessageFolder::getMyFolders($user, 'json');

	
		return $folders;
	}
	
	public function getAllowedMethod() {
	    return 'GET';
	}

	protected function malformedRequest() {
	    $this->statusCode = 400;
	}
	
	/**
	 * Überprüft, ob ein Modul eine System Authentifizierung benötigt. (z.B. zum Abfragen aller Schülerdaten)
	 * @return boolean
	 */
	public function needsSystemAuth() {
	    return true;
	}

}	

?>