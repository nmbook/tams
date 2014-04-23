<?php
require_once('../dbsetup.php');
require_once('utils.php');

 public function applyCourse($crn,$forCredit) {
        Utils::getVoid('INSERT INTO applications (crn,ta_id,time_signup,time_response,state,for_credit) VALUES (:crn,:netid,:signup,:response,:state,:credit)',
            array(':coursecrn' => $crn,
            ':netid' => $this->netid,
            ':signup' => $_SERVER['REQUEST_TIME'],
            ':response' => NULL,
            ':state' => 'pending',
            ':credit' => $forCredit));
        update();
    }
?>
<!DOCTYPE html>
<html>
<head><title>TA Listed for a 173 (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>TA Lister</h2>





