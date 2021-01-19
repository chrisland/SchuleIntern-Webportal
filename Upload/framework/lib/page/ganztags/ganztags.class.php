<?php


class ganztags extends AbstractPage {
	
	private $isAdmin = false;
	private $isTeacher = false;
	


	public function __construct() {
		
		parent::__construct(array("Lehrertools", "Ganztags - Schülerliste"));
				
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

		
		if ( $_REQUEST['action'] == 'printAll') {


			$schueler = schueler::getGanztagsSchueler();

			$num = 0;
			foreach($schueler as $item) {
				$num++;
				$html .= '<tr>';
				$html .= '<td width="4%">'.$num.'</td>';
				$html .= '<td width="15%">'.$item->getRufname().'</td>';
				$html .= '<td width="16%">'.$item->getName().'</td>';
				$html .= '<td width="3%">'.$item->getGeschlecht().'</td>';
				$html .= '<td>'.$item->getKlassenObjekt()->getKlassenName().'</td>';
				$html .= '<td width="12%">'.$item->getGanztags('print')['gruppe_name'].'</td>';
				$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_mo'].'</td>';
				$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_di'].'</td>';
				$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_mi'].'</td>';
				$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_do'].'</td>';
				$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_fr'].'</td>';
				$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_sa'].'</td>';
				$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_so'].'</td>';
				$html .= '<td width="15%">'.$item->getGanztags('print')['info'].'</td>';
				$html .= '</tr>';
			}



			$pdf = new PrintNormalPageA4WithHeader('Ganztags');
			$pdf->setPrintedDateInFooter();
			
			$html = '<table cellspacing="0" cellpadding="2" border="0.001px" style="border-color:white; border-collapse: collapse;" >
			<thead >
				<tr>
					<th width="4%"></th>
					<th width="15%" style="font-weight: bold;">Vorname</th>
					<th width="16%" style="font-weight: bold;">Name</th>
					<th width="3%"></th>
					<th style="font-weight: bold;">Klasse</th>
					<th width="12%" style="font-weight: bold;">Gruppe</th>
					<th width="5%" align="center" style="font-weight: bold;">Mo</th>
					<th width="5%" align="center" style="font-weight: bold;">Di</th>
					<th width="5%" align="center" style="font-weight: bold;">Mi</th>
					<th width="5%" align="center" style="font-weight: bold;">Do</th>
					<th width="5%" align="center" style="font-weight: bold;">Fr</th>
					<th width="5%" align="center" style="font-weight: bold;">Sa</th>
					<th width="5%" align="center" style="font-weight: bold;">So</th>
					<th width="15%" style="font-weight: bold;">Info</th>
				</tr>
			</thead>
			<tbody>'.$html.'</tbody>
			</table>';
			$pdf->setHTMLContent($html);
			
			
			$pdf->send();

			exit(0);
			


		} else if ( $_REQUEST['action'] == 'printGroups') {

			$pdf = new PrintNormalPageA4WithoutHeader('Ganztags');
			$pdf->setPrintedDateInFooter();

			$schueler = schueler::getGanztagsSchueler('ganztags.gruppe DESC, schueler.schuelerName, schueler.schuelerRufname');

			$gruppen = [];
			$query = DB::getDB()->query("SELECT *  FROM ganztags_gruppen ORDER BY sortOrder, name ");
			while($group = DB::getDB()->fetch_array($query)) {
				
				$num = 0;
				
				$html = '<h1>Gruppe: '.$group['name'].'</h1>';
				$html .= '<table cellspacing="0" cellpadding="2" border="0.001px" style="border-color:white; border-collapse: collapse;" >
					<thead >
						<tr>
							<th width="4%"></th>
							<th width="15%" style="font-weight: bold;">Vorname</th>
							<th width="16%" style="font-weight: bold;">Name</th>
							<th width="3%"></th>
							<th style="font-weight: bold;">Klasse</th>
							<th width="5%" align="center" style="font-weight: bold;">Mo</th>
							<th width="5%" align="center" style="font-weight: bold;">Di</th>
							<th width="5%" align="center" style="font-weight: bold;">Mi</th>
							<th width="5%" align="center" style="font-weight: bold;">Do</th>
							<th width="5%" align="center" style="font-weight: bold;">Fr</th>
							<th width="5%" align="center" style="font-weight: bold;">Sa</th>
							<th width="5%" align="center" style="font-weight: bold;">So</th>
							<th width="25%" style="font-weight: bold;">Info</th>
						</tr>
					</thead>
					<tbody>';
					
				$num = 0;
				foreach($schueler as $item) {
					if ($item->getGruppe() == $group['id']) {

						$num++;

						$html .= '<tr>';
						$html .= '<td width="4%">'.$num.'</td>';
						$html .= '<td width="15%">'.$item->getRufname().'</td>';
						$html .= '<td width="16%">'.$item->getName().'</td>';
						$html .= '<td width="3%">'.$item->getGeschlecht().'</td>';
						$html .= '<td>'.$item->getKlassenObjekt()->getKlassenName().'</td>';
						$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_mo'].'</td>';
						$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_di'].'</td>';
						$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_mi'].'</td>';
						$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_do'].'</td>';
						$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_fr'].'</td>';
						$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_sa'].'</td>';
						$html .= '<td width="5%" align="center">'.$item->getGanztags('print')['tag_so'].'</td>';
						$html .= '<td width="25%">'.$item->getGanztags('print')['info'].'</td>';
						$html .= '</tr>';
						
					}
				}

				$html .= '</tbody></table>';
			
				if ($num > 0) {
					$pdf->setHTMLContent($html);
				}
				
			}

			$pdf->send();
			exit(0);

		} 
		
		$schueler = schueler::getGanztagsSchueler();

		foreach($schueler as $item) {
			
			if ($item->getGeschlecht() == 'w') {
				$gender = '<i class="fa fa-venus" aria-hidden="true" style="color:red"></i>';
			} else {
				$gender = '<i class="fa fa-mars" aria-hidden="true" style="color:blue"></i>';
			}
			$html .= '<tr>';
			$html .= '<td></td>';
			$html .= '<td>'.$item->getRufname().'</td>';
			$html .= '<td>'.$item->getName().'</td>';
			$html .= '<td>'.$gender.'</td>';
			$html .= '<td>'.$item->getKlassenObjekt()->getKlassenName().'</td>';
			$html .= '<td>'.$item->getGanztags()['gruppe_name'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags('html')['tag_mo'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags('html')['tag_di'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags('html')['tag_mi'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags('html')['tag_do'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags('html')['tag_fr'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags('html')['tag_sa'].'</td>';
			$html .= '<td align="center">'.$item->getGanztags('html')['tag_so'].'</td>';
			$html .= '<td width="20%">'.$item->getGanztags()['info'].'</td>';
			$html .= '<td> <a href="index.php?page=ganztagsEdit&id='.$item->getID().'"><i class="fa fa-edit"></i> </a> </td>';
			$html .= '</tr>';
		}
		
		$acl = json_encode( $this->getAcl() );

		//$prevDays = DB::getSettings()->getValue("mensa-speiseplan-days");

		$showDays = json_encode(array(
			'Mo' => DB::getSettings()->getValue("ganztags-day-mo"),
			'Di' => DB::getSettings()->getValue("ganztags-day-di"),
			'Mi' => DB::getSettings()->getValue("ganztags-day-mi"),
			'Do' => DB::getSettings()->getValue("ganztags-day-do"),
			'Fr' => DB::getSettings()->getValue("ganztags-day-fr"),
			'Sa' => DB::getSettings()->getValue("ganztags-day-sa"),
			'So' => DB::getSettings()->getValue("ganztags-day-so")
		));
		

		eval("echo(\"" . DB::getTPL()->get("ganztags/index"). "\");");
		
	}
	
	
	public static function hasSettings() {
		return true;
	}
	
	public static function getSettingsDescription() {
		$settings = array(
			array(
				'name' => "ganztags-day-mo",
				'typ' => "BOOLEAN",
				'titel' => "Montag anzeigen?",
				'text' => ""
			),
			array(
				'name' => "ganztags-day-di",
				'typ' => "BOOLEAN",
				'titel' => "Dienstag anzeigen?",
				'text' => ""
			),
			array(
				'name' => "ganztags-day-mi",
				'typ' => "BOOLEAN",
				'titel' => "Mittwoch anzeigen?",
				'text' => ""
			),
			array(
				'name' => "ganztags-day-do",
				'typ' => "BOOLEAN",
				'titel' => "Donnerstag anzeigen?",
				'text' => ""
			),
			array(
				'name' => "ganztags-day-fr",
				'typ' => "BOOLEAN",
				'titel' => "Freitag anzeigen?",
				'text' => ""
			),
			array(
				'name' => "ganztags-day-sa",
				'typ' => "BOOLEAN",
				'titel' => "Samstag anzeigen?",
				'text' => ""
			),
			array(
				'name' => "ganztags-day-so",
				'typ' => "BOOLEAN",
				'titel' => "Sonntag anzeigen?",
				'text' => ""
			)
		);
		return $settings;
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
			if (!$_POST['ganztagsName']) {
				return false;
			}
			$order = DB::getDB()->escapeString($_POST['ganztagsSortOrder']);
			if (!$order) { $order = 1; }
			$raum = DB::getDB()->escapeString($_POST['ganztagsRaum']);
			if (!$raum) { $raum = ''; }
			$farbe = DB::getDB()->escapeString($_POST['ganztagsFarbe']);
			if (!$farbe) { $farbe = ''; }
			DB::getDB()->query("INSERT INTO ganztags_gruppen (`name`,`raum`,`farbe`, `sortOrder`)
					values (
						'" . DB::getDB()->escapeString($_POST['ganztagsName']) . "',
						'" . $raum . "',
						'" . $farbe . "',
						" . $order . "
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
						raum='" . DB::getDB()->escapeString($_POST["raum_".$o['id']]) . "',
						farbe='" . DB::getDB()->escapeString($_POST["farbe_".$o['id']]) . "',
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
				$objektHTML .= "<td><input type=\"text\" name=\"raum_" . $objektData[$i]['id'] . "\" class=\"form-control\" value=\"" . $objektData[$i]['raum'] . "\"></td>";
				$objektHTML .= "<td><input type=\"text\" name=\"farbe_" . $objektData[$i]['id'] . "\" class=\"form-control\" value=\"" . $objektData[$i]['farbe'] . "\"></td>";
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