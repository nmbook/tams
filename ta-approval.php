<!DOCTYPE html>
<html>
<head><title>Course TA Approval - TA Management System</title>
<link rel="stylesheet" type="text/css" href="front.css">
</head>
<body>
<div id="hajim_header" style="background-image:url(http://www.hajim.rochester.edu/assets/images/templates/header-background.png); width:960px;margin:auto">
<img alt="Hajim School of Engineering and Applied Sciences" src="//www.hajim.rochester.edu/assets/images/templates/header-logo.png" style="float:left;">
<img alt="Department of Computer Science" src="//www.hajim.rochester.edu/assets/images/templates/csc-header-title.png" style="float:right;">
<br clear="all">

<h2>Course TA Signups</h2>
<div id = "main" style = "margin-top: -16px;">
<?php

require_once('ta.php');
require_once('course.php');
require_once('application.php');
require_once('utils.php');
function render() {
	if (!isset($_COOKIE['netid']) || !isset($_COOKIE['password'])) {
?>
	<p>You have not logged in. Go <a href="index.html">here</a> to login.</p>
<?php
		return;	
	}
	$prof_netid = $_COOKIE['netid'];
	$password = $_COOKIE['password'];
	Utils::beginTransaction();
	try {
		$professor = Instructor::getByCredentials($prof_netid,$password);	
	}
	catch (Exception $e) {
		echo "<p>ERROR: a professor with netid $netid and password $password is not in our database.</p>\n";
		Utils::cancelTransaction();
		return;		
	}
	if (!isset($_POST["netid"]) || !isset($_POST["state"]) || (!isset($_POST["crn"]) && (!isset($_POST["course"]) || !isset($_POST["department"]) || !isset($_POST["semester"]) || !isset($_POST["year"])))) {
?>
<p> Would you like to review TA applications for a course?</p>
<form action="ta-approval.php" method="post">
<!-- TODO: add the other fields, semester, year, department? -->
<label for="department">Department:</label>
<input type="text" id="department" name="department" /><br/>
<label for="course">Course number:</label>
<input type="text" id="course" name="course" /><br/>
<label for="semester">Semester:</label>
<input type="text" id="semester" name="semester"/><br/>
<label for="year">Year:</label>
<input type="text" id="year" name="year"/><br/>
<label for="netid">NetID:</label>
<input type="text" id="netid" name="netid"/><br/>
<label for="state">State:</label>
<input type="text" id="state" name="state"/><br/>
<input type="submit"/><br/>
</form>
<a href=".">&lt;-- Back</a>
<?php
		Utils::commitTransaction();
		return;
	}
    if (isset($_POST["crn"])) {
        $crn = $_POST["crn"];
    }
    else {
        $dept = $_POST["department"];
        $course = $_POST["course"];
        $year = $_POST["year"];
        $semester = $_POST["semester"];
        try {
            $crn = Course::getCourseByName($dept, $course, $year, $semester)->getCrn();
        }
        catch (Exception $e) {
            echo "<p>ERROR: There is no $dept $course class in $semester $year</p>\n";
            Utils::cancelTransaction();
			return;
        }
    }
	if (!in_array($crn,array_map(function ($x) { return $x->getCrn(); }, $professor->getClasses()))) {
		echo "<p>ERROR: you do not teach this class, and thus cannot approve students</p>\n";
		Utils::cancelTransaction();
		return;
	}
	$netid = $_POST["netid"];
    try {
       	$application = Application::getByName($crn,$netid);
    }
    catch (Exception $e) {
        echo "<p>ERROR: student $netid never applied to course $crn</p>\n";
        Utils::cancelTransaction();
    	return;
	}
    $state = $_POST["state"];
    if ($application->getState() != $state) {
        try {
            $application->setState($state);
        }
        catch (Exception $e) {
            echo "<p>ERROR: too many students have been approved for course $crn</p>\n";
            Utils::cancelTransaction();
			return;
    	}
    }
    else {
        echo "<p>WARNING: The state of the application of $netid to $crn was already in state $state</p>\n";
    }
    Utils::commitTransaction();
}
render();
?>
</div>
<div id= "footer"></div>

</body>
</html>

