<?php

class RestMoveMsgMessage extends AbstractRest {
  
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


		// ACHTUNG: Nachrichten nicht in Gesendete verschieben lassen!
		    		    
		$moveIDs = [];

		if ( $input['list'] ) {
			$input['list'] = json_decode($input['list']);
			foreach( $input['list'] as $item) {
				if ($item->id) {
					$moveIDs[] = $item->id;
				}
			}
		}
		

		if ( $input['folder'] ) {
			$input['folder'] = json_decode($input['folder']);
		}
		$toFolder = null;
		
		$folderName = strtoupper(urldecode(DB::getDB()->escapeString($request[2])));
		$folderID = intval($request[3]);

		if ($folderName) {
			if ($folderName == 'POSTEINGANG') {
				$toFolder = MessageFolder::getFolder($user, "POSTEINGANG", 0);
			} else if ($folderName == 'ARCHIV') {
				$toFolder = MessageFolder::getFolder($user, "ARCHIV", 0);
			}
		} 
			

		if($toFolder == null && $folderID) {
			$folders = MessageFolder::getMyFolders($user);
			for($i = 0; $i < sizeof($folders); $i++) {        // In eigenen Ordnern suchen
				if($folderID == $folders[$i]->getID()) {
						$toFolder = $folders[$i];
				}
			}
		}
		if ($folderID) {
			$folderName = 'ANDERER';
		}
						
		$folder = MessageFolder::getFolder($user, $folderName, $folderID);

		if (!$folder) {
			return [
				'error' => true,
				'msg' => 'Missing Folder'
			];
		}

		if($toFolder != null) {
			$folder->moveMessages($moveIDs, $toFolder);
			return ['done' => true];
		}



		//print_r($request);
		// exit;


		// $folder = DB::getDB()->escapeString($request[2]);
		// $folderID = intval($request[3]);
		
		// $folder = MessageFolder::getFolder($user, $folder, $folderID);

		

		// if ( $input['list'] ) {

		// 	$input['list'] = json_decode($input['list']);
		// 	foreach( $input['list'] as $item) {
		// 		if ($item->id) {
		// 			$deleteIDs[] = $item->id;
		// 		}
		// 	}
		// 	//print_r($deleteIDs);

		// 	//$folder->deleteMessages($deleteIDs);
		// 	return ['done' => true];
		// }

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