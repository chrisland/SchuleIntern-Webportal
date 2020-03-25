<?php

class RestGetMsgMessage extends AbstractRest {
  
  protected $statusCode = 200;
	
	public function execute($input, $request) {

		$userID = (int)$request[1];
		$messageID = (string)$request[2];

		if (!$userID) {
			return [
				'error' => true,
				'msg' => 'Fehlende User ID'
			];
		}
		if (!$messageID) {
			return [
				'error' => true,
				'msg' => 'Fehlender MessageID'
			];
		}

		$user = new user(array('userID' => $userID));

		if (!$user) {
			return [
				'error' => true,
				'msg' => 'Fehlender User'
			];
		}

		$message = Message::getByID($messageID);	
		$obj = $message->getJSON(true);

		if (!$obj) {
			return [
				'error' => true,
				'msg' => 'Fehlende Nachricht'
			];
		}

		return $obj;
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