<?php

class RestGetMsgMessages extends AbstractRest {
  
  protected $statusCode = 200;
	
	public function execute($input, $request) {

		$userID = (int)$request[1];
		$folder = $request[2];
		$folderId = 0;
		

		if (!$folder) {
			$folder = 'Posteingang';
		}
		if ( is_int((int)$folder) && (int)$folder > 0 ) {
			$folderId = $folder;
			$folder = 'ANDERER';
		}
		//print_r( $folder ); exit;

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

		$inbox = MessageFolder::getFolder($user, $folder, $folderId );	
		$msg_temp = $inbox->getMessages();
		$messages = array();


		for ($i = 0; $i < count($msg_temp); $i++) {

			if ( method_exists($msg_temp[$i], 'getJSON') ) {
				$messages[$i] = $msg_temp[$i]->getJSON();
			}
		}
		//print_r($messages); exit;
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