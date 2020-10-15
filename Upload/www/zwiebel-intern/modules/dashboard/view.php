<?php
if ( !defined('CMS_EXEC') ) { exit; }


class DashboardView extends View {

	public function __construct($moduleFolder, $request) {
		parent::__construct($moduleFolder, $request);
	}


	public function display() {

		// Do something...

		$this->addScriptData('data', $this->get('data') );

		$this->addScript($this->getPath().'src/main.js');

		// $this->addScript($this->getPath().'my-project/dist/my-project.umd.min.js');

		$this->addScript('https://unpkg.com/vue@next');
		$this->addScript($this->getPath().'src/index.js');

		//print_r( Config::get('dbHost') );

		return $this->loadTemplate();

	}


}

?>
