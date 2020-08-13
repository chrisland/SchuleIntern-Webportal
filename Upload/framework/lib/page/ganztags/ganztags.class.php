<?php


class ganztags extends AbstractPage {
	
	private $isAdmin = false;
	private $isTeacher = false;
	


	public function __construct() {
		
		parent::__construct(array("Lehrertools", "Ganztags"));
				
		$this->checkLogin();
		
		if(DB::getSession()->isTeacher()) {
			$this->isTeacher = true;
		}
		
		if(DB::getSession()->isAdmin()) $this->isTeacher = true;
		
		if(!$this->isTeacher) {
			$this->isTeacher = DB::getSession()->isMember("Webportal_Klassenlisten_Sehen");
		}
		
		if(!$this->isTeacher) {
		    $this->isTeacher = DB::getSession()->isMember("Schuelerinfo_Sehen");
		}
		
		
	}

	public function execute() {
		
		if(!$this->isTeacher) {
			DB::showError("Diese Seite ist leider für Sie nicht sichtbar.");
			die();
		}
		
		$schueler = schueler::getGanztagsSchueler();

		foreach($schueler as $item) {
			
			if ($item->getGeschlecht() == 'w') {
				$gender = '<i class="fa fa-venus" aria-hidden="true" style="color:red"></i>';
			} else {
				$gender = '<i class="fa fa-mars" aria-hidden="true" style="color:blue"></i>';
			}
			$html .= '<tr>';
			$html .= '<td>'.$item->getVornamen().'</td>';
			//$html .= '<td>'.$item->getRufname().'</td>';
			$html .= '<td>'.$item->getName().'</td>';
			$html .= '<td>'.$gender.'</td>';
			$html .= '<td>'.$item->getKlassenObjekt()->getKlassenName().'</td>';
			$html .= '<td>'.$item->getGanztags()['gruppe_name'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags()['tag_mo'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags()['tag_di'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags()['tag_mi'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags()['tag_do'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags()['tag_fr'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags()['tag_sa'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags()['tag_so'].'</td>';
			$html .= '<td width="20%">'.$item->getGanztags()['info'].'</td>';
			$html .= '<td> <a href="index.php?page=ganztagsEdit&id='.$item->getID().'"><i class="fa fa-edit"></i> </a> </td>';
			$html .= '</tr>';
		}
		
		eval("echo(\"" . DB::getTPL()->get("ganztags/index"). "\");");
		
	}
	
	
	public static function hasSettings() {
		return false;
	}
	
	public static function getSettingsDescription() {
		return array();
	}
	
	
	public static function getSiteDisplayName() {
		return 'Ganztags';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	
	}
	
	public static function hasAdmin() {
		return true;
	}
	
	public static function getAdminGroup() {
		return false;
		//return 'Webportal_Klassenlisten_Admin';
	}
	
	public static function getAdminMenuGroup() {
		return 'Lehrertools';
	}
	
	public static function getAdminMenuGroupIcon() {
		return 'fa fa-wrench';
	}
	
	public static function getAdminMenuIcon() {
		return 'fa fa-table';
	}
	

	public static function displayAdministration($selfURL) {
		 
		if($_REQUEST['add'] > 0) {
			DB::getDB()->query("INSERT INTO ganztags_gruppen (`name`, `sortOrder`)
					values (
						'" . DB::getDB()->escapeString($_POST['ganztagsName']) . "',
						" . DB::getDB()->escapeString($_POST['ganztagsSortOrder']) . "
					) ");
		}

		if($_REQUEST['delete'] > 0) {
			DB::getDB()->query("DELETE FROM ganztags_gruppen WHERE id='" . $_REQUEST['delete'] . "'");
		}
		
		if($_REQUEST['save'] > 0) {
			$objekte = DB::getDB()->query("SELECT * FROM ganztags_gruppen ORDER BY sortOrder ASC");
			
			$objektData = array();
			while($o = DB::getDB()->fetch_array($objekte)) {
				DB::getDB()->query("UPDATE ganztags_gruppen SET 
						name='" . DB::getDB()->escapeString($_POST["name_".$o['id']]) . "',
						sortOrder='" . DB::getDB()->escapeString($_POST["sortOrder_".$o['id']]) . "'
						WHERE id='" . $o['id'] . "'");
			}
			
			
			header("Location: $selfURL");
			exit(0);
		}

		$html = '';

		$objekte = DB::getDB()->query("SELECT * FROM ganztags_gruppen ORDER BY sortOrder ASC");
		
		$objektData = array();
		while($o = DB::getDB()->fetch_array($objekte)) $objektData[] = $o;

		$objektHTML = "";
		for($i = 0; $i < sizeof($objektData); $i++) {
			$objektHTML .= "<tr>";
				$objektHTML .= "<td><input type=\"text\" name=\"name_" . $objektData[$i]['id'] . "\" class=\"form-control\" value=\"" . $objektData[$i]['name'] . "\"></td>";
				$objektHTML .= "<td><input type=\"number\" name=\"sortOrder_" . $objektData[$i]['id'] . "\" class=\"form-control\" value=\"" . @htmlspecialchars($objektData[$i]['sortOrder']) . "\"></td>";
				$objektHTML .= "<td><a href=\"#\" onclick=\"javascript:if(confirm('Soll das Objekt wirklisch gelöscht werden?')) window.location.href='$selfURL&delete=" . $objektData[$i]['id'] . "';\"><i class=\"fa fa-trash\"></i> Löschen</a></td>";
				
				$objektHTML .= "</tr>";
		}
	
		$html .= $objektHTML;
		eval("\$html = \"" . DB::getTPL()->get("ganztags/admin") . "\";");

		return $html;
	}
}


?>