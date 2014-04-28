<!DOCTYPE html>
<html>
<head><title>Course Taught by a Professor (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>Course Lister</h2>
<?php
	require_once('course.php');
	require_once('ta.php');
	if (!isset($_GET['netid']) || !isset($_GET['for_credit']) ||
		(!isset($_GET['crn']) && !(isset($_GET['dept']) && isset($_GET['number']) && $isset($_GET['year']) && isset($_GET['semester'])))) {
?>
	<form action = "apply-course.php" method="get">
	Netid: <input type="text" name="netid"><br>
	For Credit?: <input type"text" name="for_credit"><br>
	Course Department: <input type="text" name="dept"><br>
	Course Number: <input type="text" name="number"><br>
	Year: <input type="text" name="year"><br>
	Semester: <input type="text" name="semester"><br>
	<input type="submit">
	</form>
<?php
	}
	else {
		$netid = $_GET['netid'];
		$for_credit = $_GET['for_credit'];
		try {
			$ta = TA::getByNetID($netid);
		}
		catch (TamsException $e) {
			echo 'Caught Exception1: ', $e->getMessage(), "\n";
		}
		if (isset($_GET['crn'])) {
			$crn = $_GET['crn'];
		}
		else {
			try {
				$crn = Course::getCoursesByName($_GET['dept'],$_GET['number'],$_GET['year'],$_GET['semester'])->getCrn();	
			}
			catch (TamsException $e) {
				echo 'Caught Exception2: ', $e->getMessage(), "\n";
			}
		}
		try {
			$ta->apply($crn,$for_credit);
		}
		catch (TamsException $e) {
			echo 'Caught Exception3: ', $e->getMessage(), "\n";	
		}
	}
?>
</body>
</html>

