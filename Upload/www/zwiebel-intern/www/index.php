<?php
session_start();

define('CMS_EXEC',1);
define('DS',DIRECTORY_SEPARATOR);
define('FOLDER_ROOT',dirname(__FILE__).DS.'..'.DS);
define('FOLDER_WWW',__DIR__.DS);
define('URL', "http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]");



// System Klassen MVC einbinden
$systemLoad = [
	'helper/config.php',
	'system/controller.php',
	'system/model.php',
	'system/view.php',
	'helper/database.php',
	'helper/factory.php'
];


foreach ($systemLoad as &$load) {
	
	$load = FOLDER_ROOT.'lib'.DS.$load;
	if (file_exists($load)) {
		include_once($load);
	} else {
		die('Error with Class Load: '.$load);
	}

}

// Import Config
include(FOLDER_ROOT.'config/config.php');


// Bridge to Schule-Intern
include(FOLDER_ROOT.'bridge/config.php');


// $_GET und $_POST zusammenfasen, $_COOKIE interessiert uns nicht.
$request = array_merge($_GET, $_POST);


// echo "<pre>";
// print_r($request);
// echo "</pre>";


// Request Startseite
if (!$request['view']) {
	$request['view'] = 'dashboard';
}

// Controller erstellen
$controllerName = ucfirst($request['view']).'Controller';
$controllerFile = FOLDER_ROOT.'modules'.DS.$request['view'].DS.'controller.php';

if (file_exists($controllerFile)) {
	include_once($controllerFile);
	$controller = new $controllerName($request);
} else {
	$controller = new Controller($request);
}


if (!$controller) {
  die('Error with Controller');
}

// Inhalt der Webanwendung ausgeben.
echo $controller->display();

?>