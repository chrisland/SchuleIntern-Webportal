<?php

class RestSetVplan extends AbstractRest {
	protected $statusCode = 200;

	public function execute($input, $request) {
	

		//echo $_POST['file'];


		if ( !$_POST['file'] ) {
			return [
				'error' => true,
				'msg' => 'Fehlende Daten'
			];
		}

		$file = str_split(preg_replace("/[^\r\n]/", "", $_POST['file'] ));

		//$file = str_getcsv ( $_POST['file'],  "," , '"' , "\n"  );

		echo "<pre>";
		print_r($file);
		echo "</pre>";

		return [];
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