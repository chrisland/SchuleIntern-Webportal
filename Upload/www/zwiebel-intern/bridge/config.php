<?php

$path = '../../../data/config/config.php';

if ( file_exists($path) ) {
  include($path);
  $config = new GlobalSettings;
  
  if ($config) {
    Config::set('dbHost', $config->dbSettigns['host'] );
    Config::set('dbUser', $config->dbSettigns['user'] );
    Config::set('dbPass', $config->dbSettigns['password'] );
    Config::set('dbName', $config->dbSettigns['database'] );
  }

}
