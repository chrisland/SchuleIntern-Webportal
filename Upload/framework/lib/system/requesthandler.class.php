<?php

/**
 * Verwaltet die Aktionen des Benutzers.
 * @author Christian Spitschka
 */
class requesthandler {
    /**
     * Erlaubte Aktionen.
     * @var array
     */
  private static $actions = [
  	'notenverwaltung' => [
  		'NotenverwaltungIndex',
  		'NotenEingabe',
  	    'NotenverwaltungZeugnisse',
  	    'NotenverwaltungRecoverZuordnung',
  	    'NotenZeugnisMV',
  	    'NotenWahlunterricht',
  	    'NotenZeugnisKlassenleitung',
  	    'NotenBerichte',
  	    'NotenRespizienz'
  	],
    'ausweis' => [
        'Ausweis'
    ],
      'oauth2' => [
          'oAuth2Auth'
      ],
  	'files' => [
  		'FileDownload'
  	],
  	'skin' => [
  		'SkinSettings'
  	],
    /**  'nextcloud' => [
        'nextcloud'
    ], **/
    'json' => [
        'jsonApi'
    ],
    /*'allinklmail' => [
        'AllInkMail'
    ],*/
    'absenzen' => [
      'absenzenberichte',
      'absenzenlehrer',
      'absenzensekretariat',
      'absenzenstatistik',
      'absenzenschueler',
        'AbsenzenMain'
    ],
  	'messages' => [
  		'MessageInbox',
  		'MessageRead',
  		'MessageCompose',
  		'MessageSendRights',
  		'MessageAttachmentDownload',
  		'MessageConfirm'
  	],
    'kondolenzbuch' => [
        'kondolenzbuch'
    ],
    'administration' => [
      'administration',
      'administrationactivatepages',
      'administrationasvimport',
      'administrationbadmails',
      'administrationcreateusers',
      'administrationimportmgsd2',
      'administrationsettings',
      'administrationunknownmails',
      'administrationusermatch',
      'administrationusers',
      'administrationusersync',
      'administrationmodule',
      'administrationcron',
      'administrationgroups',
      'AdminMailSettings',
      'AdminUpdate',
      'AdminBackup',
      'AdminDatabase',
      'AdministrationEltern',
      'AdminDatabaseUpdate',
      'AdminExtensions'
    ],
    'aufeinenblick' => [
      'aufeinenblick',
    ],
    'ausleihe' => [
      'ausleihe',
      'ausleiheMonitor',
    ],
  	'beurlaubung' => [
  	    'beurlaubungantrag'
  	],
  	'digitalSignage' => [
  		'digitalSignage',
  		'digitalSignageLayoutPowerpoints',
  	    'digitalSignageWebsites'
  	],
    'beobachtungsbogen' => [
      'beobachtungsbogen',
      'beobachtungsbogenadmin',
      'beobachtungsbogenklassenleitung',
    ],
    'dokumente' => [
      'dokumente',
    ],
    'elternsprechtag' => [
      'elternsprechtag',
    ],
  	'schaukasten' => [
  		'schaukasten'
  	],

    'respizienz' => [
        'respizienz'
    ],
  		
    'kalender' => [
      'klassenkalender',
      'extKalender',
      'andereKalender',
      'geticsfeed',
      'terminuebersicht'
    ],
    'klassenlisten' => [
      'klassenlisten',
    ],
    'ganztags' => [
      'ganztags',
      'ganztagsEdit',
      'ganztagsCalendar'
    ],
    'krankmeldung' => [
      'krankmeldung',
    ],
    'laufzettel' => [
      'laufzettel',
    ],
    'legal' => [
      'impressum',
      'datenschutz'
    ],
    'loginlogout' => [
      'login',
      'logout',
    ],
    'mebis' => [
      'mebis',
    ],
    'mensa' => [
      'mensaSpeiseplan'
    ],
    'office365' => [
      'office365',
      'office365users',
      'office365info',
        'Office365Meetings'
    ],
    'oldpages' => [
      'homeuseprogram',
    ],
    'projektverwaltung' => [
      'projektverwaltung',
    ],
    'register' => [
      'elternregister',
    ],
    'stundenplan' => [
        'stundenplan'

    ],
    'system' => [
      'errorPage',
      'GetMathCaptcha',
      'index',
      'info',
      'Update',
      'Backup'
    ],
    'userprofile' => [
      'changeuseridinsession',
      'forgotPassword',
      'userprofile',
      'userprofilemylogins',
      'userprofilepassword',
      'userprofilesettings',
      'userprofileuserimage',
        'TwoFactor'
    ],
      'lerntutoren' => [
          'Lerntutoren'
      ],
    'vplan' => [
      'updatevplan',
      'vplan'
    ],
  	'test' => [
  		'test'
  	],
  	'klassentagebuch' => [
  		'klassentagebuch',
  	    'klassentagebuchauswertung'
  	],
  	'print' => [
  		'printSettings'
  	],
  	'schulinfo' => [
  		'schulinfo'
  	],
  	'schulbuch' => [
  		'schulbuecher'
  	],
  	'schuelerinfo' => [
  		'schuelerinfo',
  		'AngemeldeteEltern'
  	],
    'support' => [
        'adminsupport'
    ],
    'wlan' => [
        'WLanTickets'
    ]

  ];

  private static $allAdminGroups = [];

  public function __construct($action, $_request) {

    PAGE::setFactory( new FACTORY() );

    $allowed = false;
    $type = false;
    
    // First load Page
    $allowed = self::loadPage($action);
    if ($allowed) {
      $type = 'page';
    }
    
    // Load extensions
    if (!$allowed) {
      $view = 'default';
      if ($_request['view']) {
        $view = $_request['view'];
      }
      $extension = self::loadExtensions($action, $view);
      if ($extension['allowed'] == true && $extension['classname']) {
        $allowed = true;
        $type = 'extension';
        $action = $extension['classname'];
      }
    }
  
    if($allowed) {
      try {
        $page = new $action($_request);
        
        if ($type == 'extension' && $_request['task']) {
          
          $taskMethod = 'task'.ucfirst($_request['task']);
          if ( method_exists($page, 'task'.ucfirst($_request['task']) )) {
            $postData = [];
            $_post = json_decode(file_get_contents("php://input"), TRUE);
            if ($_post) {
              foreach($_post as $key => $val) {
                $postData[stripslashes(strip_tags(htmlspecialchars($key, ENT_IGNORE, 'utf-8')))] = stripslashes(strip_tags(htmlspecialchars($val, ENT_IGNORE, 'utf-8')));
              }
            }
            $page->$taskMethod($postData);
            exit;
          } else {
            new errorPage('Task was not found! <br> ( task: '.$taskMethod.' )');
            exit;
          }
        } else {
          
          $page->execute();
        }
      }
      catch(Throwable $e) {
        echo "<b>!!!" . $e->getMessage() . "</b> in Line " . $e->getLine()  . " in " . $e->getFile() . "<br />";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
      }
    } else {
      new errorPage('There is no Page');
      die();
    }
    PAGE::kill(true);
    
  }

  /**
   * Erlaute Aktion am RequestHandler
   * 
   * @return array
   */
  public static function getAllowedActions() {
    $ps = [];
    // Active Pages
    foreach(self::$actions as $f => $pages) {
      for($p = 0; $p < sizeof($pages); $p++) {
        $ps[] = $pages[$p];
      }
    }
    // Active Modules
    // $result = DB::getDB()->query('SELECT `id`,`folder` FROM `extensions` WHERE `active` = 1 ');
		// while($row = DB::getDB()->fetch_array($result)) {
    //   $ps[] = $row['folder'];
    // }

    return $ps;
  }

  
  /**
   * Load Page
   * 
   * @param unknown $action
   * @return boolean
   */
  public static function loadPage($action) {
      
      $allowed = false;

      foreach(self::$actions as $f => $pages) {
        for($p = 0; $p < sizeof($pages); $p++) {
            
          if($pages[$p] == $action) {
            require_once ('../framework/lib/page/abstractPage.class.php');
            include_once('../framework/lib/page/' . $f . '/' . $pages[$p] . '.class.php');
            $allowed = true;
          }
        }
      }
      return $allowed;
  }

  /**
   * Load Extensions
   * 
   * @param unknown $action
   * @return boolean
   */
  public static function loadExtensions($action, $view = 'default') {
      
    $allowed = false;

    $module = DB::getDB()->query_first("SELECT `id`,`folder` FROM extensions WHERE `folder` = '".$action."'" );
    if ($module && $module['folder'] && $view) {
      if (file_exists('../extensions/'.$module['folder'].'/'.$view.'.php')) {
        require_once ('../framework/lib/page/abstractPage.class.php');
        include_once('../extensions/'.$module['folder'].'/'.$view.'.php');
        $allowed = true;
      }
    }

    if ($allowed) {
      return [
        'allowed' => true,
        'classname' => $module['folder'].ucfirst($view),
        'folder' => $module['folder'],
        'view' => $view
      ];
    }

    return false;
    
    
}

 

}

?>
