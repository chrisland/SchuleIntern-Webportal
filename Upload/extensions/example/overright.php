<?php

class exampleOverright extends AbstractPage {
	
	public static function getSiteDisplayName() {
		return 'Example Module - Overright';
	}

	public function __construct($request = [], $extension = []) {
		parent::__construct(array( self::getSiteDisplayName() ), false, false, false, $request, $extension);
		$this->checkLogin();
	}

	public function execute() {

		//$this->getRequest();
		//$this->getAcl();

		
		$this->render([
			"tmpl" => "overright"
		]);

	}

}
