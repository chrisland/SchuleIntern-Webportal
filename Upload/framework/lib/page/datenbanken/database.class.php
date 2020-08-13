<?php


class database extends AbstractPage {

	private $sqlSchule;
	
	public function __construct() {
		$this->needLicense = true;
		
		parent::__construct(array("Unterricht", "Datenbanken"));
		$this->sqlSchule = new mysqldevdatabases();
		
		$this->checkLogin();
		
		if(!(DB::getSession()->isTeacher() || DB::getSession()->isPupil())) {
			new errorPage("Dieser Bereich ist nur für Schüler und Lehrer.");
			exit(0);
		}
		
		if(!$this->sqlSchule->connect()) {
			eval("DB::getTPL()->out(\"" . DB::getTPL()->get("database/noconnection") . "\");");
			PAGE::kill(true);
			//exit(0);
		}
	}

	public function execute() {
		$userID = DB::getSession()->getData("userID");
		
		switch($_GET['action']) {
			case "addDatabase":
				$this->createDatabase($userID,$_POST['databaseName']);
			break;
			
			case "deleteDatabase":
				$dbID = intval($_GET['dbID']);
				$database = DB::getDB()->query_first("SELECT * FROM database_database WHERE databaseID='" . $dbID . "'");
				
				if(strtolower($database['databaseUserID']) == strtolower($userID)) {
					$this->deleteDatabase($dbID);
				}
				else die("Nicht erlaubt: " . $database['databaseUserID'] . " - " . $userID);
			break;
			
			case "addUser":
				$this->createUser($userID,substr(md5(rand()),0,4));
			break;
			
			case "deleteUser":
				$dUserID = intval($_GET['userID']);
				$user = DB::getDB()->query_first("SELECT * FROM database_users WHERE userID='" . $dUserID . "'");
				if(strtolower($user['userUserID']) == $userID) {
					$this->deleteUser($_GET['userID']);
				}
				else die("No Access");				
			break;
			
			case "addRights":
				$write = ($_POST['write'] == '1');
				$dUserID = intval($_POST['userID']);
				$dbID = intval($_POST['dbID']);
				$user = DB::getDB()->query_first("SELECT * FROM database_users WHERE userID='" . $dUserID . "'");
				$database = DB::getDB()->query_first("SELECT * FROM database_database WHERE databaseID='" . $dbID . "'");
				
				
				
				
				if($user['userUserID'] == $userID && $database['databaseUserID'] == $userID) {
					if($write) {
						$this->setChangeRight($dUserID,$dbID);
					}
					else {
						$this->setReadRight($dUserID,$dbID);
					}
				}			
			break;
			
			case "removeRight":
				$dUserID = intval($_GET['userID']);
				$dbID = intval($_GET['dbID']);
				$user = DB::getDB()->query_first("SELECT * FROM database_users WHERE userUserID='" . $dUserID . "'");
				$database = DB::getDB()->query_first("SELECT * FROM database_database WHERE databaseID='" . $dbID . "'");
				
				
				// Debugger::debugObject([$user,$database,$userID],1);
				
				if($user['userUserID'] == $userID && $database['databaseUserID'] == $userID) {
					$this->removeRights($dUserID,$dbID);
				}
				else die("No Access");	
			break;
		
		}
		
		// Alles anzeigen
		
		// Eigene Datenbanken
		$dbList = "";
		$databases = DB::getDB()->query("SELECT * FROM database_database WHERE databaseUserID='" . $userID . "'");
		
		$dbArray = array();
		
		while($db = DB::getDB()->fetch_array($databases)) {
			$dbArray[] = $db;
			$access = DB::getDB()->query("SELECT * FROM database_database NATURAL JOIN database_user2database NATURAL JOIN database_users WHERE databaseID=" . $db['databaseID']);
			$dbList .= "<li><u>" . DB::getGlobalSettings()->siteNamePlain . "-D" . $db['databaseID'] . " (" . $db['databaseName'] . ")</u> <a href=\"#\" onclick=\"javascript:if(confirm('Soll die Datenbank wirklich gelöscht werden?')) location.href='index.php?page=database&action=deleteDatabase&dbID=" . $db['databaseID'] . "'\"><i class=\"fa fa-trash\"></i></a><br />";			
			while($a = DB::getDB()->fetch_array($access)) {
				$dbList .= "<i class=\"fa fa-user\"></i> " . DB::getGlobalSettings()->siteNamePlain . "-U" . $a['userID'] . " - " . (($a['rights'] == 0) ? ("<i class=\"fa fa-eye\"></i>") : ("<i class=\"fa fas fa-pencil-alt\"></i>")) . " - <a href=\"index.php?page=database&gplsession={$_GET['gplsession']}&action=removeRight&userID=" . $a['userID'] . "&dbID=" . $a['databaseID'] . "\">Rechte entfernen</a></li>";
			}
			
		}
		
		if($dbList == "") $dbList = "<li><i>Bisher keine</i></li>";
		
		// Eigene Benutzer
		$userList = "";
		$users = DB::getDB()->query("SELECT * FROM database_users WHERE userUserID='" . $userID . "'");
		
		$userArray = array();
		
		while($user = DB::getDB()->fetch_array($users)) {
			$userArray[] = $user;
			$access = DB::getDB()->query("SELECT * FROM database_database NATURAL JOIN database_user2database NATURAL JOIN database_users WHERE userID=" . $user['userID']);
			$userList .= "<li><u>" . DB::getGlobalSettings()->siteNamePlain . "-U" . $user['userID'] . "</u> <a href=\"#\" onclick=\"javascript:if(confirm('Soll der Benutzer wirklich gelöscht werden?')) location.href='index.php?page=database&gplsession={$_GET['gplsession']}&action=deleteUser&userID=" . $user['userID'] . "'\"><i class=\"fa fa-trash\"></i></a><br />";			
			$userList .= "<i class=\"fa fa-key\"></i> Passwort: " . $user['userPassword'] . "</li>";
			
		}
		
		if($userList == "") $userList = "<li><i>Bisher keine</i></li>";
		
		// Select für Datenbank
		
		$selectDB = "";
		for($i = 0; $i < sizeof($dbArray); $i++) {
			$selectDB .= "<option value=\"" . $dbArray[$i]['databaseID'] . "\">" . DB::getGlobalSettings()->siteNamePlain . "-D" . $dbArray[$i]['databaseID'] . " (" . $dbArray[$i]['databaseName'] . ")</option>";
		}
		
		$selectUser = "";
		for($i = 0; $i < sizeof($userArray); $i++) {
			$selectUser .= "<option value=\"" . $userArray[$i]['userID'] . "\">" . DB::getGlobalSettings()->siteNamePlain . "-U" . $userArray[$i]['userID'] . "</option>";
		}	
		
		eval("echo(\"" . DB::getTPL()->get("database/database") . "\");");
		
	}
	
	
	private function createDatabase($userid,$name) {
		DB::getDB()->query("INSERT INTO database_database (databaseName,databaseUserID) values('" . addslashes($name) . "','" . $userid . "')");
		
		$newName = DB::getGlobalSettings()->siteNamePlain . '-D' . DB::getDB()->insert_id();
		
		$this->sqlSchule->connect();
		$this->sqlSchule->query("CREATE DATABASE `" . $newName . "`");
		
		return $newName;
	}
	
	private function createUser($userid,$password) {
		DB::getDB()->query("INSERT INTO database_users (userPassword,userUserID) values('" . addslashes($password) . "','" . $userid . "')");
		
		$newName = DB::getGlobalSettings()->siteNamePlain . '-' . 'U' . DB::getDB()->insert_id();
		
		$this->sqlSchule->connect();
		$this->sqlSchule->query("CREATE USER `" . $newName . "`@'%' IDENTIFIED BY '" . $password . "'");
		
		return $newName;
	}
	
	private function deleteUser($userid) {
		DB::getDB()->query("DELETE FROM database_users WHERE userID='" . $userid . "'");
		DB::getDB()->query("DELETE FROM database_user2database WHERE userID='" . $userid . "'");
		
		
		$this->sqlSchule->connect();
		$this->sqlSchule->query("DROP USER `" . DB::getGlobalSettings()->siteNamePlain . '-' . "U" . $userid . "`@'%'");
	}
	
	private function deleteDatabase($dbID) {
		DB::getDB()->query("DELETE FROM database_database WHERE databaseID='" . $dbID . "'");
		DB::getDB()->query("DELETE FROM database_user2database WHERE databaseID='" . $dbID . "'");
		
		$this->sqlSchule->connect();
		$this->sqlSchule->query("DROP DATABASE `" . DB::getGlobalSettings()->siteNamePlain . '-D' . $dbID . "`");
	}
	
	private function setReadRight($userID,$databaseID) {
		DB::getDB()->query("INSERT INTO database_user2database (userID,databaseID,rights) values('" . $userID . "','" . $databaseID . "',0) ON DUPLICATE KEY UPDATE rights=0");
		
		$this->sqlSchule->connect();
		$this->sqlSchule->query("GRANT SELECT ON `" . DB::getGlobalSettings()->siteNamePlain . '-D' . $databaseID . "` . * TO '" . DB::getGlobalSettings()->siteNamePlain . "-U" . $userID . "'@'%';");		
	}
	
	private function setChangeRight($userID,$databaseID) {
		DB::getDB()->query("INSERT INTO database_user2database (userID,databaseID,rights) values('" . $userID . "','" . $databaseID . "',1) ON DUPLICATE KEY UPDATE rights=1");
		
		$this->sqlSchule->connect();
		$this->sqlSchule->query("GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,INDEX,ALTER,CREATE TEMPORARY TABLES,CREATE VIEW,EVENT,TRIGGER,SHOW VIEW,CREATE ROUTINE,ALTER ROUTINE,EXECUTE ON `" . DB::getGlobalSettings()->siteNamePlain . '-D' . $databaseID . "` . * TO '" . DB::getGlobalSettings()->siteNamePlain . "-U" . $userID . "'@'%';");		
	}
	
	private function removeRights($userID,$databaseID) {
		DB::getDB()->query("DELETE FROM database_user2database WHERE userID='" . $userID . "' AND databaseID='" . $databaseID . "'");
		
		$this->sqlSchule->connect();
		$this->sqlSchule->query("REVOKE ALL PRIVILEGES ON `" . DB::getGlobalSettings()->siteNamePlain . '-D' . $databaseID . "` . * FROM '" . DB::getGlobalSettings()->siteNamePlain . '-' . "U" . $userID . "'@'%';");		
	}	
	
	public static function notifyUserDeleted($userID) {
		// Datenbanken und User löschen des Benutzers
		$databases = DB::getDB()->query("SELECT * FROM database_database WHERE databaseUserID='" . $userID . "'");
		while($db = DB::getDB()->fetch_array($databases)) {
			$this->deleteDatabase($db['databseID']);
		}
		
		$users = DB::getDB()->query("SELECT * FROM database_users WHERE userUserID='" .$userID . "'");
		while($user = DB::getDB()->fetch_array($users)) {
			$this->deleteUser($user['userID']);
		}
	}
	
	public static function hasSettings() {
		return false;
	}
	
	/**
	 * Stellt eine Beschreibung der Einstellungen bereit, die für das Modul nötig sind.
	 * @return array(String, String)
	 * array(
	 * 	   array(
	 * 		'name' => "Name der Einstellung (z.B. formblatt-isActive)",
	 *		'typ' => ZEILE | TEXT | NUMMER | BOOLEAN,
	 *      'titel' => "Titel der Beschreibung",
	 *      'text' => "Text der Beschreibung"
	 *     )
	 *     ,
	 *     .
	 *     .
	 *     .
	 *  )
	 */
	public static function getSettingsDescription() {
		return array();
	}
	
	
	public static function getSiteDisplayName() {
		return 'Datenbankverwaltung';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	
	}
	
	public static function onlyForSchool() {
		return [];
	}
}


?>