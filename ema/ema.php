<?php

//require_once('globals.php');
//require_once('functions.php');

//echo $_SERVER['SERVER_ADDR'];
//phpinfo();
//exit;

error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();


require_once("constants.php");

require_once("functions.php");
require_once("dbConfig.php");
require_once("config.php");

require_once("globals.php");

define('directLoginKey', '43admafeifaqgfasdFDedfq34qfa#1sa');
/*
define('USCIC_SURVEY', 1);
define('POST_PARAM_SUID', 'suid');
define('POST_PARAM_PRIMKEY', 'primkey');
define('POST_PARAM_LANGUAGE', 'language');
define('POST_PARAM_MODE', 'mode');
define('POST_PARAM_SE', 'se');
define('POST_PARAM_PRELOAD', 'pd');

define('POST_PARAM_NEW_PRIMKEY', 'newpk');

define('SURVEY_OPEN', 1);
*/


function gen_password2($length = 8) {
    mt_srand((double)microtime()*1000000);
    $password = "";
    $chars = "abcdefghijkmnpqrstuvwxyz";
    for($i = 0; $i < 4; $i++) {
        $x = mt_rand(0, strlen($chars) -1);
        $password .= $chars{$x};
    }
    $chars = "23456789";
    for($i = 0; $i < 2; $i++) {
        $x = mt_rand(0, strlen($chars) -1);
        $password .= $chars{$x};
    }
    return $password;
}

function listSurveys(){
	$content = '<select name=' . POST_PARAM_SUID . ' class="form-control" style="width:160px">';
	$content .= '
<option value=1 SELECTED>EMA_test</option>
<option value=3>EMA_eating_questions</option>
<option value=5>EMA_stress</option>
<option value=6>EMA_anger</option>
<option value=8>EMA_happy</option>
<option value=9>EMA_sad</option>
<option value=10>EMA_annoyed</option>
<option value=11>Depression&Anger&Stress</option>
<option value=12>Fatigue&PositiveAffect&Stress</option>
<option value=13>Anxiety&Vigor&Stress</option>
<option value=14>Fatigue&Vigor&Stress</option>
<option value=15>Depression&Anxiety&Stress&PositiveAffect</option>
<option value=16>Timeout</option>
<option value=17>EMA_stress_noaudio</option>
<option value=19>PCR_R001</option>
<option value=20>PCR_R002</option>
<option value=21>PCR_R003</option>
<option value=22>PCR_R000</option>
<option value=23>PCR_R004</option>
<option value=24>PCR_R005</option>';
	$content .='</select>';
	return $content;
}


$content = '';
if (loadvar('q') != ''){
  $jsonStr = loadvar('q');
  $jsonArray = json_decode($jsonStr, true); 
  if (isset($jsonArray['c'])){
    if ($jsonArray['c'] == 'startsurvey'){  //alert phone
		$phoneAddress = '192.168.0.108:2226';
		if (isset($jsonArray['androidid']) != ''){
		  $query = 'select * from ema_phones where phoneid="' . $jsonArray['androidid'] . '"';
		  global $db;
  		  $result = $db->selectQuery($query);
		  if ($result != null){
             $row = $db->getRow($result);
			 $phoneAddress = $row['ip'] . ':' . $row['port'];
		  }
        }
		if (isset($jsonArray['phoneip']) != ''){
			$phoneAddress = $jsonArray['phoneip'];
        }
        echo 'http://' . $phoneAddress . '?q=' . $jsonStr;
		echo file_get_contents('http://' . $phoneAddress . '?q=' . urlencode($jsonStr));
        exit;
    }
    elseif ($jsonArray['c'] == 'retrieveanswer'){  //retrieve value

    }
  }
}
elseif (loadvar('p') == 'txtinfo'){
  $query = 'select * from ema_phones order by ts desc';
  global $db;
  $result = $db->selectQuery($query);
  ob_clean();
  header('Content-disposition: attachment; filename=txtinfo.txt');
  header('Content-type: text/plain');
  if ($result != null){
      while ($row = $db->getRow($result)){
  	  	echo $row['phoneid'] . "\t" . $row['ip'] . "\t" . $row['port'] . "\t" . $row['ts'] . "\n";
	  }
  }
  else {
	echo 'no phones connected';
  }
  exit;
}
elseif (loadvar('p') == 'sync'){
    $phoneid = loadvar('phoneid');
    $port = loadvar('port');
    $ip = loadvar('ip');
    if ($phoneid != '' && $port != '' && $ip != ''){
  	  $query = 'replace into ema_phones (phoneid, ip, port) values ("' . $phoneid . '", "' . $ip . '", "' . $port . '")';
      global $db;
      $db->selectQuery($query);
  	  $content = '<div class="alert alert-info" role="info">This server has been synced with this phone at ' . $ip . ' and port ' . $port . '.</div>';
    }
    else {
  	  $content = '<div class="alert alert-info" role="info">This server has <b>not</b> been synced with this phone.</div>';
    }
}
elseif (loadvar('p') == 'noinit'){
	$content = '<div class="alert alert-danger" role="alert">This phone has not been setup yet. Please hand it to an administrator.</div>';
}
elseif (loadvar('p') == 'ping'){
	$phoneid = loadvar('phoneid');

	$content .= '<ol class="breadcrumb">
	  <li><a href=./ema.php?p=info>Info</a></li>
	  <li>Ping: ' . $phoneid . '</li>
	</ol>';


	$content .= 'Ping phone with survey: ';
	$content .= '<form id="form" method="post" action="ema.php">';
	$content .= '<input type=hidden name=p value="ping.res">';
	$content .= '<input type=hidden name=phoneid value="' . $phoneid . '">';
	$content .= listSurveys();
	$content .= '<input type=submit class="btn btn-default">';
	$content .= '</form>';

}
elseif (loadvar('p') == 'ping.res'){
	$phoneid = loadvar('phoneid');
	$content = '<ol class="breadcrumb">
	  <li><a href=./ema.php?p=info>Info</a></li>
	  <li><a href=./ema.php?p=ping&phoneid=' . $phoneid . '>Ping: ' . $phoneid . '</a></li>
	  <li>Submit</a></li>
	</ol>';
	$suid = loadvar('suid');
//	$content .= '<h4>Ping: ' . $phoneid . '</h4>';
    $empathid = gen_password2(8);
	$q = '{"id":"123","c":"startsurvey","suid":"' . $suid . '","server":"http://' . $_SERVER['SERVER_ADDR'] . '/ema/ema.php","androidid":"' . $phoneid . '","empathid":"' . $empathid . '","alarm":"true"}';
//	$content .= $q;	
	$content .= '<tr><td><h4><a href=./ema.php?q=' . $q . '>Send ping to the phone</a></h4>';
}
elseif (loadvar('p') == 'info'){
  $content = '<h4>Server address: ' . $_SERVER['SERVER_ADDR'];
  $content .= '</h4>Synced phones:<br/>';
  $query = 'select * from ema_phones order by ts desc';
  global $db;
  $result = $db->selectQuery($query);
  if ($result != null){
	  $content .= '<table class="table">'; 
	  $content .= '<tr><th>Phone id</th><th>Ip address</th><th>Port #</th><th>Timestamp</th></tr>'; 

      while ($row = $db->getRow($result)){
  	  	$content .= '<tr><td><a href=./ema.php?p=ping&phoneid=' . $row['phoneid'] . '>' . $row['phoneid'] . '</a></td><td>' . $row['ip'] . '</td><td>' . $row['port'] . '</td><td>' . $row['ts'] . '</td></tr>';
	   
	  }
      $content .= '</table>';

  }
  $content .= '<hr><a href=./ema.php?p=info>refresh</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href=./ema.php?p=phpinfo>phpinfo</a>';

}
elseif (loadvar('p') == 'phpinfo'){
  $content = phpinfo();
}
elseif (loadvar('p') == 'error'){
  $message = loadvar('msg');
  $content = '<div class="alert alert-danger" role="alert">An error occured. ' . $message . '<br/><br/>Please hand this phone to an administrator.</div>';

}
elseif (loadvar('p') == 'notime'){
	$content = 'Thank you. We will try again later.';
}
elseif (loadvar('p') == 'start'){
  $rtid = loadvar('rtid');
  $date = loadvar('date');
  $language = loadvar('language');
  $suid = loadvar('suid');
  if ($suid == ''){ $suid = 1; }

  $dosurvey = true;
  if ($suid == '999'){ //timeout (morning message)!
	 $dosurvey = false;
  }
  if ($suid == '998'){ //evening message!
	 $dosurvey = false;
  }
  if ($suid == '997'){ //evening message!
	 $dosurvey = false;
  }
  if ($suid == '996'){ //evening message!
	 $dosurvey = false;
  }


  //date:  2016/01/11 15:41:27

  //check to see if this is not the first time (init survey with isolation)
/*
  $dosurvey = false;
  $daynumber = 0;
  if (isDay0Completed($rtid)){
    $daynumber = getCurrentDayNumber($rtid) + 1;  //add one so we start with day 1   (day 0: init)
  }
  else { 
    //start day 0 (init)
    $daynumber = 0;
    $dosurvey = true;
  }
  //check to see if this is between 18:00 and 23:59
  if ($daynumber > 0){
   // echo $date . '<br/>';
	$date1 = date('H:i a', strtotime($date));
//	$date2 = date('H:i a', strtotime("6:00 pm"));
//	$date3 = date('H:i a', strtotime("11:59 pm"));
	$date2 = date('H:i a', strtotime("10:00 am"));
	$date3 = date('H:i a', strtotime("11:59 am"));
	if ($date1 > $date2 && $date1 < $date3)
	{
       $dosurvey = true;
	}
  }*/
  $daynumber = date('Hmdhis');
  if ($dosurvey){
      //is it not completed yet??
      if (isDayCompleted($rtid, $daynumber)){
          $content = Language::completedAlreadyText($language);
      }
      else { //not completed.. start or restart
		  $content = '';
//		  $content = 'start survey - ' . $suid . ' - with: -' . $rtid . ' - <br/>';

//		  $content .= '<h4>Can you answer questions right now?</h4><br/><br/>';
		  $content .= '<form id="form" method="post" action="index.php">';
		  $content .= '<input type=hidden name=' . POST_PARAM_SE . ' value="' . addslashes(USCIC_SURVEY) . '">';
		  $content .= '<input type=hidden name=' . POST_PARAM_PRIMKEY . ' value="' . addslashes(encryptC($rtid, directLoginKey)) . '">';
		  $content .= '<input type=hidden id=' . POST_PARAM_SUID . ' name=' . POST_PARAM_SUID . ' value="' . $suid . '">';
		  $content .= '<input type=hidden name=' . POST_PARAM_LANGUAGE . ' value="' . addslashes($language) . '">';
		  //$content .= '<input type=hidden name=' . POST_PARAM_PRELOAD . ' value="' . encodeSession($member->getPreload($mobile, $tablet)) . '">';
		  $content .= '<input type=hidden name=' . POST_PARAM_NEW_PRIMKEY . ' value="1">';
     	  $content .= '<input type=hidden name=ss value=1>';
		  $content .= '<table width=100%><tr><td valign=top>';
		  $content .= '<input type=submit class="btn btn-default" style="width:150px" value="Yes">';
		  $content .= '</form>';
		  $content .= '</td><td valign=top>';

		  $content .= '<form method="post" action="ema.php">';
   		  $content .= '<input type=hidden name=p value="notime">';
		  $content .= '<input type=submit class="btn btn-default" style="width:150px"value="No">';
		  $content .= '</form>';
		  $content .= '</td></tr></table>';
		  $content .= '<script>';
		  $content .= '$(document).ready(function(){ $("form:first").submit(); }); ';
		  $content .= '</script>';
      }
  }
  else {
	$language = loadvar('language');
  	if ($language == ''){ $language = 1; }

	if ($suid == '999'){ //timeout!
	    $content = 'This is a morning encouragement message.';
	}
	elseif($suid == '998'){ //timeout!
	    $content = 'This is an evening encouragement message.';
	}
	elseif($suid == '997'){ //timeout!
	    $content = 'Breathe in through your nose and out through your mouth. Is there something about your current environmentthat is increasing distractions/confusion for Jane. Is the TV too loud?';
	}
	elseif($suid == '996'){ //timeout!
	    $content = 'Don’t forget to breathe. Can you modify your surroundings to reduce Jane’s restlessness?';
	}
	else {
	    $content = Language::notOpenYetText($language);
	    //MESSAGE: NOT OPEN
	}
  }

}
elseif (loadvar('p') == 'showselfie'){
  $content = '<script>  function showSelfie(){ return "65:1";} </script>';

}
elseif (loadvar('p') == 'charger'){
  $language = loadvar('language');
  if ($language == ''){ $language = 1; }
  $content = Language::chargerText($language);
}

elseif (loadvar('p') == 'upload'){
  //filename = file
/*
	$query = 'replace into onco_pictures (primkey, variablename, picture) VALUES (';
	$query .= '"' . addslashes($id) . '", ';
	$query .= '"' . addslashes($fieldname) . '", ';
	//$query .= '"' . addslashes(base64_decode(implode("", $_POST))) . '") ';

	$query .= 'AES_ENCRYPT("' . addslashes(base64_decode(implode("", $_POST))) . '", "basbas")) ';
*/
//	$db->executeQuery($query);

/*
ob_start();
var_dump($_GET);
var_dump($_POST);
var_dump($_FILES);
$result = ob_get_clean();

file_put_contents('/tmp/test2.txt', $result);*/

$uploaddir = '/tmp/';
$uploadfile = $uploaddir . 't' . basename($_FILES['file']['name']);
move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile);


		$query = 'replace into onco_pictures (primkey, variablename, picture) VALUES (';
		$query .= '"' . addslashes( loadvar('rtid') ) . '", ';
		$query .= '"' . addslashes('onco_pic') . '", ';
        $imageStr = file_get_contents($uploadfile);
		$query .= 'AES_ENCRYPT("' . addslashes(($imageStr      )) . '", "f8kdq83j#3ff123")) ';
unlink($uploadfile);
		global $db;
		$db->selectQuery($query);



}
elseif (loadvar('p') == 'showpic'){
    global $db;
    echo 'show';
	$query = 'select *, AES_DECRYPT(picture, "f8kdq83j#3ff123") as picture1 from onco_pictures where primkey="65:1" and variablename = "onco_pic"';
	echo $query;
    $result = $db->selectQuery($query);
    if ($result != null){    
      $row = $db->getRow($result);
      ob_clean();
      header('Content-type: image/jpg');
      if ($row['picture'] != null){
        print($row['picture1']);
      }
      else {  //display 'empty' image
//          echo file_get_contents('images/nopicture.png');
      }
	}

    exit;
}
else {

	$primkey = generateRandomPrimkey(8);


	$content .= '<form id="form" method="post" action="index.php">';
	$content .= 'Select a survey and language to start:<hr>';




	$content .= 'survey: ';
	$content .= listSurveys();

	$content .= 'language: <select name=' . POST_PARAM_LANGUAGE . ' class="form-control" style="width:160px">';
	$content .= '<option value=1 SELECTED>English</option>';
//	$content .= '<option value=2>Español</option>';
//	$content .= '<option value=3>中國</option>';
	$content .='</select>';
	$content .= '<input type=hidden name=' . POST_PARAM_SE . ' value="' . addslashes(USCIC_SURVEY) . '">';
	$content .= '<input type=hidden name=' . POST_PARAM_PRIMKEY . ' value="' . addslashes(encryptC($primkey, directLoginKey)) . '">';
//	$content .= '<input type=hidden id=' . POST_PARAM_SUID . ' name=' . POST_PARAM_SUID . ' value="' . '1' . '">';

	//$content .= '<input type=hidden name=' . POST_PARAM_LANGUAGE . ' value="' . addslashes($member->getLanguage()) . '">';
	//$content .= '<input type=hidden name=' . POST_PARAM_PRELOAD . ' value="' . encodeSession($member->getPreload($mobile, $tablet)) . '">';
	$content .= '<input type=hidden name=' . POST_PARAM_NEW_PRIMKEY . ' value="1">';
	$content .= '<input type=submit class="btn btn-default" value="Start">';
	$content .= '</form>';
	$content .= '<hr><b>{this screen will not be shown to respondents, the app will start with the right language and version}</b>';
}
showHeader();
echo $content;
showFooter();




//function encryptC($text, $salt) { 
//    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
//} 

//function decryptC($text, $salt){ 
//    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
//}
/*
function generateRandomPrimkey($length = 8) {
    $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}*/

function showHeader(){
echo '
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="images/favicon.ico">

    <title>UAS SMS</title><noscript><meta http-equiv="refresh" content="0; URL=/onco/nojavascript.php"></noscript>
    <!-- Bootstrap core CSS -->
		<link rel="stylesheet" type="text/css" href="bootstrap/dist/css/bootstrap.css">

    <!-- Custom scripts and styles for this template --><script type="text/javascript" charset="utf-8" language="javascript" src="bootstrap/assets/js/jquery.js"></script>
    <link href="js/formpickers/css/bootstrap-formhelpers.min.css" rel="stylesheet">
                  <link href="css/uscicadmin.css" rel="stylesheet">
                  <link href="bootstrap/css/sticky-footer-navbar.css" rel="stylesheet">

<script type="text/javascript">
    if(typeof window.history.pushState == \'function\') {
        window.history.pushState({}, "Hide", "index.php");
    }    
</script>
      
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="bootstrap/assets/js/html5shiv.js"></script>
      <script src="bootstrap/assets/js/respond.min.js"></script>
    <![endif]-->
    
    <script src="js/hover-dropdown.js"></script>
    <script type="text/javascript" src="js/tooltip.js"></script>
    <script type="text/javascript" src="js/popover.js"></script>    
    <script type="text/javascript" src="js/modal.js"></script>
    </head>
                    <body>
                    <div id = "wrap"><div class = "container">
<br/>
<div id="uscic-mainpanel" class="panel panel-default uscic-mainpanel"><div id="uscic-mainbody" class="panel-body">

';

}

function showFooter(){
  echo '</div></div></div></div></body></html>';
}

function isDay0Completed($rtid){
  return isDayCompleted($rtid, 0);
}

function isDayCompleted($rtid, $daynumber){
  global $db;
  $query = 'select count(*) as cnt from onco_data where variablename="endtime" and answer is not null and primkey ="' . $rtid . ':' . $daynumber . '"';
  $result = $db->selectQuery($query);
  $row = $db->getRow($result);
  return $row['cnt'] > 0;
}

function getField($rtid, $day, $fieldname){
  global $db;
  $datakey = 'eimavEwkoi12381kdDw';
  $query = 'select *, aes_decrypt(answer, "' . $datakey . '") as ans from onco_data where variablename="' . $fieldname . '" and primkey="' . $rtid . ':' . $day . '"';
  $result = $db->selectQuery($query);
  $row = $db->getRow($result);
  return $row['ans'];
}


function getCurrentDayNumber($rtid){
  $startdate = strtotime(getField($rtid, 0, 'endtime'));
  $now = time(); // or your date as well
  $datediff = $now - $startdate;
  return floor($datediff/(60*60*24));
}


class Language {

    static function chargerText($language = 1) {
        $text = array(1 => 'Please do not forget to charge your Phone and Band tonight.<br/><br/>Thank you!', 
					  2 => 'No se le olvide cargar su teléfono y la banda esta noche.<br/><br/>Muchas gracias!', 
                      3 => '今晚請不要忘記將您的手機和手帶充電, 謝謝.');
        return $text[$language];
    }

    static function notOpenYetText($language = 1) {
        $text = array(1 => 'The survey for today is not open yet. Please check back after 6pm.', 
					  2 => 'La encuesta de hoy aún no está abierta. Favor de revisar abertura después de las 6pm.', 
                      3 => '今天的問卷還未開放. 請於晚上六點後再次確認');
        return $text[$language];
	}

    static function completedAlreadyText($language = 1){
        $text = array(1 => 'Thank you! The survey for today has been completed. Please check back tomorrow after 6pm. <br/><br/>Please do not forget to charge your Phone and Band tonight. <br/><br/>Thank you!',
					  2 => 'Thank you! The survey for today has been completed. Favor de revisar de nuevo  <b>mañana <b> después de las 6 pm. <br/><br/>No se le olvide cargar su teléfono y la banda esta noche.<br/><br/>Muchas gracias!', 
                      3 => 'Thank you! The survey for today has been completed. Please check back tomorrow after 6pm. 今晚請不要忘記將您的手機和手帶充電, 謝謝.');
        return $text[$language];
    }

}

?>
