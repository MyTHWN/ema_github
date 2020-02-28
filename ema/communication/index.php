<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

set_include_path(dirname(getcwd()));


require_once('constants.php');
require_once('functions.php');


require_once('dbConfig.php');
require_once('config.php');
require_once('database.php');
require_once('communication.php');

date_default_timezone_set(Config::timezone());

$p = loadvar('p');
$urid = loadvar('urid');
$db = new Database();
$communication = new Communication();


$returnValue = 'error';
if ($p == 'upload'){ //upload data!
  $communication->storeUpload($_POST['query'], $urid);  
  $communication->importTable($_POST['query']);
  $returnValue = 'ok';
}
elseif($p == 'updateavailable'){ //is there an update available?
  $returnValue = 'no';
  if (sizeof($communication->getUserQueries($urid)) > 0){
    $returnValue = 'yes';
  }  
  if (sizeof($communication->getUserScripts($urid)) > 0){
    $returnValue = 'yes';
  }  
}
elseif($p == 'receive'){ //receive the update
  $returnValue = '';
  if (sizeof($communication->getUserQueries($urid)) > 0){ //sql
    foreach($communication->getUserQueries($urid) as $row){
        if (trim($row['sqlcode']) != ''){
          $returnValue .= '1!~!~!' . ($row['sqlcode']) . "!~!~!";
        }
    }
  }
  if (sizeof($communication->getUserScripts($urid)) > 0){ //scripts
    foreach($communication->getUserScripts($urid) as $row){
        if (trim($row['sqlcode']) != ''){
          $returnValue .= '2~' . $row['filename'] . '!~!~!' . ($row['sqlcode']) . "!~!~!";
        }
    }
   
  }   
  
}
elseif($p == 'datareceived'){
  $communication->setUpdateReceived($urid);
  $returnValue = 'ok';
}


echo $returnValue;

?>