<?php

class RestGetLogin extends AbstractRest {
	protected $statusCode = 200;

	public function execute($input, $request) {

		if (!$input['username'] || !$input['password']) {
			return [
				'error' => true,
				'msg' => 'Fehlender Benutzername oder Passwort.'
			];
		}

		$user = DB::getDB()->query_first("SELECT `userCachedPasswordHash` FROM users WHERE userName LIKE '" . DB::getDB()->escapeString(trim(($input['username']))) . "'");

		if($this->check_password($user['userCachedPasswordHash'], $input['password'])) {
			return [
				'success' => true
			];
		}

		return [
			'error' => true,
			'msg' => 'Falscher Benutzername oder Password!'
		];

		exit;
	}

	public function check_password($hash, $password) {
		$full_salt = substr($hash, 0, 29);
		$new_hash = crypt($password, $full_salt);
		return ($hash == $new_hash);
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

	public function needsUserAuth() {
		return false;
	}


}	

?>