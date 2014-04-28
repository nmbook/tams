<!DOCTYPE html>
<html>
<head><title>Course Taught by a Professor (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>Course Lister</h2>
<?php
	require_once('course.php');
	require_once('ta.php');
	if (!isset($_GET['netid']) || !isset($_GET['for_credit'])) {
?>
	<form action = "apply-course.php" method="get">
	Netid: <input type="text" name="netid"><br>
	For Credit?: <input type"text" name="for_credit"><br>
	<input type="submit">
	</form>
<?php
	}
	elseif (!isset($_GET['crn']) && !(isset($_GET['dept']) && isset($_GET['number']) && $isset($_GET['year']) && isset($_GET['semester']))) {
?>
<form action="apply-course.php" method="get">
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
		$ta = TA::getByNetID($netid);
		if (isset($_GET['crn'])) {
			$crn = $_GET['crn'];
		}
		else {
			$crn = Course::getCoursesByName($_GET['dept'],$_GET['number'],$_GET['year'],$_GET['semester'])->getCrn();	
		}
		$ta->apply($crn,$for_credit);
	}
?>
</body>
</html>

