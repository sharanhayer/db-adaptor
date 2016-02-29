<?php
// Sample file for SlimPDO

// Include composer autoload file
require_once(dirname(__DIR__) . '/vendor/autoload.php');

// Configration
$db_config = array(
  'host' => 'localhost', //DB Host
  'name' => 'phpSlim', //DB name
  'user' => 'admin',
  'password' => 'admin'
);
// Load PDO Adaptor
$db = new JattDB\JattPDO($db_config);
echo "now \$db can be used to execute all database intaractions";
?>
