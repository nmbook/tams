<!DOCTYPE html>
<html>
<head><title>Course TA Approval - TA Management System</title></head>
<body>
<h1>TA Management System on Betaweb</h1>
<h2>Course TA Signups</h2>
<?php

require_once('ta.php');
require_once('course.php');
require_once('application.php');
require_once('utils.php');
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
}
else {
	$guard = true;
	Utils::beginTransaction();
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
			$guard = false;
			Utils::cancelTransaction();
		}
	}
	$netid = $_POST["netid"];
	if ($guard) {
		try {
			$application = Application::getByName($crn,$netid);
		}
		catch (Exception $e) {
			echo "<p>ERROR: student $netid never applied to course $crn</p>\n";	
			$guard = false;
			Utils::cancelTransaction();
		}
	}
	$state = $_POST["state"];
	if ($guard) {
		if ($application->getState() != $state) {
			try {
				$application->setState($state);
			}
			catch (Exception $e) {
				echo "<p>ERROR: too many students have been approved for course $crn</p>\n";
				$guard = false;
			}
		}
		else {
			echo "<p>WARNING: The state of the application of $netid to $crn was already in state $state</p>\n";
		}
	}
	if ($guard) {
		Utils::commitTransaction();
	}
}
?>
</body>
</html>

