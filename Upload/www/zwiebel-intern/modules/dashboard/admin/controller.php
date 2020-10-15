<?php
if ( !defined('CMS_EXEC') ) { exit; }


class DashboardController extends Controller {


	public function __construct($request) {
		parent::__construct($request);
	}

	
	public function show($view) {
		
		// if ( !$_SESSION['userID'] ) {
		// 	return false;
		// }
		$data = $this->model->getItems($_SESSION['userID']);
		$view->assign('data', $data);

		return true;
	}

}

?>
