<?php

class RestGetMsgMessages extends AbstractRest {
  
  protected $statusCode = 200;
	
	public function execute($input, $request) {

		//print_r($input); //POST
		// print_r($request);
		// echo '>>>>>>>>>>';

		print_r($request); exit;

		$userID = (int)$request[1];
		$folder = (int)$request[2];

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

		$inbox = MessageFolder::getFolder($user, 'Posteingang');	

		$msg_temp = $inbox->getMessages();
		$messages = array();

		for ($i = 0; $i < count($msg_temp); $i++) {
			$messages[$i] = $msg_temp[$i]->getJSON();
		}

		return $messages;
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
 No newline at end of file
