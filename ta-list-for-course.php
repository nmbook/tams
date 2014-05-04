<?php

require_once('ta.php');
require_once('course.php');


if (!isset($_GET["crn"]) && (!isset($_GET["course"]) || !isset($_GET["department"]) || !isset($_GET["semester"]) || !isset($_GET["year"]))) {
?>
<!DOCTYPE html>
<html>
<head><title>Course TA Signups - TA Management System</title></head>
<body>
<h1>TA Management System on Betaweb</h1>
<h2>Course TA Signups</h2>
<p> Would you like to look up the TAs for a course?</p>
<form action="ta-list-for-course.php" method="get">
<!-- TODO: add the other fields, semester, year, department? -->
<label for="department">Department:</label>
<input type="text" id="department" name="department" /><br/>
<label for="course">Course number:</label>
<input type="text" id="course" name="course" /><br/>
<label for="semester">Semester:</label>
<input type="text" id="semester" name="semester"/><br/>
<label for="year">Year:</label>
<input type="text" id="year" name="year"/><br/>
<input type="submit"/><br/>
</form>
<a href=".">$lt;-- Back</a>
<?php
}
else {
	if (isset($_GET["crn"])) {
		$course = Course::getCourseByCrn($_GET["crn"]);
		$tas = $course->getApprovedApplications();
	}
	else {
		try {
		    $tas = TA::getByClass($_GET["semester"], $_GET["year"], $_GET["department"], $_GET["course"]);
		} catch (TamsException $ex) {
		    echo "Failure: $ex<br/>";
		    $tas = array();
		}
	}
	if (count($tas) > 0) {
	    echo "<p>Showing TAs signed up to TA $class:</p>";
	    echo '<table cellspacing="1"><thead><th width="150">Net ID</th><th width="150">Name</th><th width="250">E-Mail</th><th>Class Year</th></thead><tbody>';
	    foreach ($tas as $ta) {
	        echo "<tr><td>{$ta->getNetID()}</td><td>{$ta->getName()}</td><td>{$ta->getEmail()}</td><td>{$ta->getClassYear()}</td></tr>";
	    }
	    echo '</tbody></table>';
	}
?>
<a href="ta-list-for-course.php">&lt;-- Back</a>
<?php
}
?>
</body>
</html>

