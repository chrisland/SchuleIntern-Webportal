<?php

class RestGetAcl extends AbstractRest {
	protected $statusCode = 200;

	public function execute($input, $request) {

		$module = $request[1];
		if (!$module) {
			return [
				'error' => true,
				'msg' => 'Fehlendes Modul!'
			];
		}
		$result = ACL::getAcl($this->user, $module );
		
		
		// echo "<pre>";
		// print_r($result);
		// echo "</pre>";


		if( isset($result['aclID']) && intval($result['aclID']) > 0 ) {
			return [
				'acl' => $result
			];
		} else {
			return [
				'error' => true,
				'msg' => 'Es konnte keine ACL gefunden werden!',
				'aclBlank' => ACL::getBlank()
			];
		}

		return [];
		//exit;
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
		return false;
	}

	public function needsUserAuth() {
		return true;
	}

	public function aclModuleName() {
		return 'kalenderAllInOne';
	}


}	

?>