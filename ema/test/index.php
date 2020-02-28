<?php

require_once('../constants.php');
require_once('../config.php');
require_once('../functions.php');

// build simple test page to launch survey from outside of NubiS

// define simple header
$returnStr = '
<!DOCTYPE html>
<html><title>NubiS - Simple survey test page</title><body>
<form method=post action=../index.php>
<center><h2>Test survey</h2>
<br/><br/>';

// instruct to start survey
$returnStr .= '<input type=hidden value=' . USCIC_SURVEY . ' name=' . POST_PARAM_SE . '>';

// clear any previous test session(s)
$returnStr .= '<input type=hidden value=1 name=' . POST_PARAM_RESET_TEST . '>';
        
// generate a random primary key to be used
$returnStr .= '<input type=hidden name=' . POST_PARAM_PRIMKEY . ' value="' . addslashes(encryptC(generateRandomPrimkey(8), Config::directLoginKey())) . '">';

// start a new interview each time
$returnStr .= '<input type=hidden name=' . POST_PARAM_NEW_PRIMKEY . ' value="1">';            

// set language to be used
$returnStr .= '<input type=hidden name=language value=2>';

// set interview mode to be used (1=CAPI, 2=CATI, 3=CASI, 4=CADI)
$returnStr .= '<input type=hidden name=mode value=3>';

// survey execution mode (0=normal, 1=test mode)
$returnStr .= '<input type=hidden name=executionmode value=1>';

// define start button
$returnStr .= '
<input type=submit value="Start">
</form></center>
</body></html>
';

// display test page
echo $returnStr;



?>
