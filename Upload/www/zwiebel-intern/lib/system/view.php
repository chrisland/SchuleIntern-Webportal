<?php
class View{

	// Pfad zum Template
	private $path = false;
	
	// Name des Templates, in dem Fall das Standardtemplate.
	private $template = 'default';
	
	// Request (POST & GET)
	public $request = false;
	
	/**
	 * Enthält die Variablen, die in das Template eingebetet
	 * werden sollen.
	 */
	private $_ = array();

	// Javascript daten die eingebunden werden
	private $scripts = array();
	private $scriptsData = array();
	

	
	
	public function __construct($moduleFolder, $request){
		$this->path = $moduleFolder;

		$this->request = $request;
	}
	
	/**
	 * Ordnet eine Variable einem bestimmten Schl&uuml;ssel zu.
	 *
	 * @param String $key Schlüssel
	 * @param String $value Variable
	 */
	public function assign($key, $value){
		$this->_[$key] = $value;
	}


	/**
	 * Setzt den Namen des Templates.
	 *
	 * @param String $template Name des Templates.
	 */
	public function setTemplate($template = 'default'){
		$this->template = $template;
	}

	/**
	 * Gibt die Variable an das Template
	 *
	 * @param String $key Schlüssel
	 */
	public function get($key){
		if ( $this->_[$key] ) {
			return $this->_[$key];
		}
		return false;
	}

	public function getPath() {
		if ( $this->path ) {
			return $this->path;
		}
		return false;
	}

	public function loadJSON() {
		header("Content-Type: application/json;charset=utf-8");
		echo json_encode($this->_);
	}

	/**
	 * Das Template-File laden und zurückgeben
	 *
	 * @param string $tpl Der Name des Template-Files (falls es nicht vorher 
	 * 						über steTemplate() zugewiesen wurde).
	 * @return string Der Output des Templates.
	 */
	public function loadTemplate(){
		
		$tpl = $this->template;
		// Pfad zum Template erstellen & überprüfen ob das Template existiert.
		$file = $this->path.DS.'tmpl'. DS.$this->template . '.php';	
		$exists = file_exists($file);

		$header = FOLDER_WWW.'snippets'.DS.'header.php';
		$footer = FOLDER_WWW.'snippets'.DS.'footer.php';
		
		if ($exists){

			$script = $this->getScriptData().$this->getScript();
			
			// Der Output des Scripts wird n einen Buffer gespeichert, d.h.
			// nicht gleich ausgegeben.
			ob_start();
			
			if (file_exists($header)) {
				include $header;
			}
			
			// Das Template-File wird eingebunden und dessen Ausgabe in
			// $output gespeichert.
			include $file;
			
			
			
			
			if (file_exists($footer)) {
				include $footer;
			}

			//echo $this->getJS();
			
			$output = ob_get_contents();
			ob_end_clean();
			
			
			// Output zurückgeben.
			return $output;
		} else {
			// Template-File existiert nicht-> Fehlermeldung.
			return '<h3>Could not find template!</h3>';
		}
	}
	
	public function display() {
	
		return $this->loadTemplate();
			
	}
	

	public function addScriptData ($key = false, $data = false) {
		if (!$key) {
			return false;
		}
		$this->scriptsData[$key] = $data;
		return true;
	}

	public function getScriptData(){
		if ($this->scriptsData) {
			return '<script>var globals = '.json_encode($this->scriptsData).';</script>';
		}
		return '<script>var globals = {};</script>';
	}
	
	public function addScript($file){
		if (!$file) {
			return false;
		}
		array_push($this->scripts, $file);
		return true;
	}
	
	public function getScript(){
		$html = '';
		foreach( $this->scripts as $script ) {
			$file = file_get_contents($script);
			if ($file) {
				$html .= '<script>'.$file.'</script>';
			}
		}
		return $html;
	}
	
	
	
	
}
?>