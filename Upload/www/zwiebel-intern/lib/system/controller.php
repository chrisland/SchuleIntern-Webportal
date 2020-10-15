<?php
class Controller {

	private $request = null;
	private $template = '';
	private $task = '';
	private $folder = '';	
	private $view = null;
	public $model = null;

	/**
	 * Konstruktor, erstellet den Controller.
	 *
	 * @param Array $request Array aus $_GET & $_POST.
	 */
	public function __construct($request){
		//$this->view = new View();
		$this->request = $request;
		$this->template = !empty($request['layout']) ? $request['layout'] : 'default';
		$this->task = !empty($request['task']) ? $request['task'] : 'show';
		$this->admin = !empty($request['admin']) ? DS.'admin' : '';
		$this->folder = !empty($request['view']) ? FOLDER_ROOT.'modules/'.$request['view'].DS : '';
	}

	/**
	 * Methode zum anzeigen des Contents.
	 *
	 * @return String Content der Applikation.
	 */
	public function display(){
		

		$this->model = $this->getModel();
		
		//$moduleFolder = FOLDER_ROOT.'modules/'.$this->request['view'];

		$this->view = $this->getView($this->folder, $this->request);
		
		$this->view->setTemplate($this->template);
			
		if( method_exists($this, $this->task ) ) {
			$this->{$this->task}($this->view);
		}		

		return $this->view->display();

	}
	
	public function show() {
		return true;
	}
	
	
	

	
	private function getView($viewFile, $request) {
		
		$viewFile = $this->folder.$this->admin.'/view.php';
		$viewName = ucfirst($this->request['view']).'View';

		if (file_exists($viewFile)) {
		  include_once($viewFile);
		  return new $viewName($this->folder.$this->admin, $request);
		} else {
		  die('No View');
		  return false;
		}
		
		if (!$view) {
		  die('Error with View');
		  return false;
		}
		
	}
	
	
	public function getModel() {
	
		$modelName = ucfirst($this->request['view']).'Model';
		$modelFile = FOLDER_ROOT.'modules/'.$this->request['view'].$this->admin.'/model.php';
		
		if (file_exists($modelFile)) {
		  include_once($modelFile);
		  return new $modelName();
		} else {
		  return false;
		}
		
		if (!$model) {
		  return false;
		}

	
	
	}
	
	
}
?>