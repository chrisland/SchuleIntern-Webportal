<?php

class RestDeleteMsgMessage extends AbstractRest {
  
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

		//print_r($request);
		// exit;


		$folder = DB::getDB()->escapeString($request[2]);
		$folderID = intval($request[3]);
		
		$folder = MessageFolder::getFolder($user, $folder, $folderID);

		

		if ( $input['list'] ) {

			$input['list'] = json_decode($input['list']);
			foreach( $input['list'] as $item) {
				if ($item->id) {
					$deleteIDs[] = $item->id;
				}
			}
			//print_r($deleteIDs);

			//$folder->deleteMessages($deleteIDs);
			return ['done' => true];
		}

		return [
			'error' => true,
			'msg' => 'Fehler'
		];

		// $inbox = MessageFolder::getFolder($user, $folder, $folderId );	
		// $msg_temp = $inbox->getMessages();
		// $messages = array();


		// for ($i = 0; $i < count($msg_temp); $i++) {

		// 	if ( method_exists($msg_temp[$i], 'getJSON') ) {
		// 		$messages[$i] = $msg_temp[$i]->getJSON();
		// 	}
		// }
		// //print_r($messages); exit;
		// return $messages;

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
	    return true;
	}

}	

?>