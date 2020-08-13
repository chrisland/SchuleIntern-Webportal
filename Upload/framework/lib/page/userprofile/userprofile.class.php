<?php

class userprofile extends AbstractPage {
	public function __construct() {
		parent::__construct(array("Benutzerprofil", "Mein Profil"));
		
		$this->checkLogin();
	}
	
	private function encrypt_decrypt($action, $string) {
	    $output = false;
	    
	    $encrypt_method = "AES-256-CBC";
	    $secret_key = 'wrf2398fh39erufvn39487vn39487n';
	    $secret_iv = 'dbvldkjfnvbaskhjg9eugh39478gn3';
	    
	    $key = hash('sha256', $secret_key);
	    
	    $iv = substr(hash('sha256', $secret_iv), 0, 16);
	    if ( $action == 'encrypt' ) {
	        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	        $output = base64_encode($output);
	    } else if( $action == 'decrypt' ) {
	        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
	    }
	    
	    return $output;
	}

	public function execute() {

	   if(!DB::getSession()->getUser()->userCanChangePassword()) {
	       new errorPage("Kein Zugriff auf das Profil.");
	   }
		
		if(DB::getGlobalSettings()->elternUserMode == "ASV_CODE" && $_GET['addKind'] == 1) {
			$key = DB::getDB()->query_first("SELECT * FROM eltern_codes WHERE codeText='" . DB::getDB()->escapeString($_POST['code']) . "'");
			if($key['codeSchuelerAsvID'] != "") {
				DB::getDB()->query("INSERT INTO eltern_email (elternEMail, elternSchuelerAsvID, elternUserID) values('" . DB::getSession()->getData("userName") . "','" . $key['codeSchuelerAsvID'] . "','" . DB::getUserID() . "') ON DUPLICATE KEY UPDATE elternSchuelerAsvID=elternSchuelerAsvID");
				if($key['codeUserID'] == 0 || $key['codeUserID'] == "") {
					DB::getDB()->query("UPDATE eltern_codes SET codeUserID='" . DB::getUserID() . "' WHERE codeID='" . $key['codeID'] . "'");
				}
				else {
					DB::getDB()->query("UPDATE eltern_codes SET codeUserID='" . $key['codeUserID'] . "," . DB::getUserID() . "' WHERE codeID='" . $key['codeID'] . "'");
				}
			}
			header("Location: index.php?page=userprofile");
			exit(0);
		}
		
		if(DB::getGlobalSettings()->elternUserMode == "ASV_CODE" && $_GET['action'] == 'changeName') {
			DB::getDB()->query("UPDATE users SET userFirstName='" . DB::getDB()->escapeString($_POST['vorname']) . "', userLastName='" . DB::getDB()->escapeString($_POST['name']) . "' WHERE userID='" . DB::getSession()->getUserID() . "'");
			header("Location: index.php?page=userprofile");
			exit(0);
		}
		
	    if($_REQUEST['action'] == 'changeNotification') {
	        
	        $newStatus = $_POST['newstatus'] > 0;
	        
	        $result = ['status' => true, 'newStatus' => $newStatus];
	        
	        
	        
	        DB::getSession()->getUser()->setReceiveEMail($newStatus);
	        
	        header("Content-type: text/json");
	        echo json_encode($result);
	        
	        exit(0);
	    }
	    
	    if($_REQUEST['action'] == 'getMailJson') {

	        $result = [
	            'success' => true,
	            'mail' => null,
	            'canChangeMail' => DB::getSession()->getUser()->canChangeMailAddress(),
	            'userReceiveMail' => false
	        ];
	        
	        $userMail = DB::getSession()->getUser()->getEMail();
	        
	        if($userMail != "") {
	            $result['mail'] = $userMail;
	            $result['userReceiveMail'] = DB::getSession()->getUser()->receiveEMail();
	        }
	        
	        header("Content-type: text/json");
	        echo json_encode($result);
	        
	        exit(0);
	    }
	    
	    if($_REQUEST['action'] == "updateSig") {
	        DB::getSession()->getUser()->setSignature($_REQUEST['sig']);
	        header("Location: index.php?page=userprofile");
	        exit(0);
			}
			
			if($_REQUEST['action'] == "updateAutoresponse") {
			//	print_r($_REQUEST); exit;

				//DB::getSession()->getUser()->setAutoresponse($_REQUEST['value']);

				//print_r($_REQUEST['value']);

				DB::getSession()->getUser()->setAutoresponse($_REQUEST['value']);

				

				header("Location: index.php?page=userprofile");
				exit(0);
			}

			if($_REQUEST['action'] == "updateAutoresponseText") {
				DB::getSession()->getUser()->setAutoresponseText($_REQUEST['sig']);
				header("Location: index.php?page=userprofile");
				exit(0);
			}
	    
	    
	    
	    if($_REQUEST['action'] == 'changeMailStep1') {
	        
	        $result = [
	            'result' => false,
	            'newMail' => '',
	            'error' => '',
	            'code' => ''
	        ];
	        
	        $newMail = strtolower(trim($_POST['newMail']));
	        
	        if(!filter_var($newMail, FILTER_VALIDATE_EMAIL)) {
	            $result['error'] = 'Mailadresse ungültig!';
	        }
	        elseif(user::getUserByEMail($newMail) != null) {
	            $result['error'] = 'Mailadresse bereits vergeben!';
	        }
	        elseif(!DB::getSession()->getUser()->canChangeMailAddress()) {
	            $result['error'] = 'Benutzer darf seine E-Mailadresse nicht ändern!';
	        }
	        else {
	        
    	        $code = rand(10000,99999);
    	        
    	        $codeCrypted = $this->encrypt_decrypt("encrypt", $code);
    	        
    	        $message = "Der Code zur Bestätigung der E-Mailadresse auf " . DB::getGlobalSettings()->siteNamePlain . " lautet: " . $code;
    	        $mail = new email($_REQUEST['newMail'], "Bestätigungscode für " . DB::getGlobalSettings()->siteNamePlain, $message);
    	        $mail->sendInstantMail();
	        
	        
    	        $result['result'] = true;
    	        $result['newMail'] = $this->encrypt_decrypt("encrypt", $newMail);
    	        
    	        $result['code'] = $codeCrypted;
    	        
	        }
	        
	        header("Content-type: text/json");
	        echo json_encode($result);
	        
	        exit(0);
	        
	    }
	    
	    if($_REQUEST['action'] == 'changeMailStep2') {
	        
	        $result = [
	            'result' => false
	        ];
	        
	        $newMail = $this->encrypt_decrypt("decrypt", $_POST['newMailCrypt']);
	        $codeSolution = $this->encrypt_decrypt("decrypt", $_POST['codeCryptedValue']);
	        $codeGiven = $_POST['code'];
	        
	        
	        
	        if($codeSolution == $codeGiven) {
	            DB::getSession()->getUser()->changeMail($newMail);
	            $result = [
	                'result' => true
	            ];
	        }
	        
	        header("Content-type: text/json");
	        echo json_encode($result);
	        
	        exit(0);
	        
	    }
	    
	    if($_REQUEST['action'] == "deleteAccount") {
	        
	        if(DB::getSession()->getUser()->isEltern() && DB::getGlobalSettings()->elternUserMode == 'ASV_CODE') {
	            DB::getSession()->delete();
	            
	            DB::getSession()->getUser()->deleteUser();     // Loggt auch aus.
	        }
	        
	        header("Location: index.php");
	        
	        exit(0);

	    }
	    
	    
		
		$groupList = implode(", ",DB::getSession()->getGroupNames());
		$groupList = str_replace("G_","",$groupList);
				
		$data = DB::getSession();
				
		$username = $data->getData("userName");
		$realname = $data->getData("userFirstName") . " " . $data->getData("userLastName");
		$userID = $data->getUserID();
		
		$elternHTML = "";
		if(DB::getSession()->isEltern()) {
			$elternHTML = "<tr><td><b>Kinder</td><td>";
			$kinder = DB::getSession()->getElternObject()->getMySchueler();
			for($i = 0; $i < sizeof($kinder); $i++) {
				$elternHTML .= $kinder[$i]->getName() . ", " . $kinder[$i]->getRufname() . " (Klasse " . $kinder[$i]->getKlasse() . ")<br />";
			}
			
			if(DB::getGlobalSettings()->elternUserMode == "ASV_CODE") {
				$elternHTML .= "<b>Kind hinzufügen:</b>";
				$elternHTML .= "<form action=\"index.php?page=userprofile&addKind=1\" method=\"post\">";
				$elternHTML .= "<input type=\"text\" name=\"code\" class=\"form-control\" placeholder=\"Registrierungscode XXXXX-XXXXXXXXX\">";
				$elternHTML .= "<button type=\"submit\" class=\"btn btn-default\"><i class=\"fa fa-plus\"></i> Kind hinzufügen</button></form>";
				
				$vorname = $data->getData("userFirstName");
				$name = $data->getData("userLastName");
			}
			
			$elternHTML .= "</td></tr>";
		}
				
		eval("echo(\"".DB::getTPL()->get("userprofile/userprofile")."\");");
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
		return '';
	}
	
	/**
	 * Liest alle Nutzergruppen aus, die diese Seite verwendet. (Für die Benutzeradministration)
	 * @return array(array('groupName' => '', 'beschreibung' => ''))
	 */
	public static function getUserGroups() {
		return array();
	
	}
	
	public static function siteIsAlwaysActive() {
		return true;
	}
}


?>