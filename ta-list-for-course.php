<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="front.css">
<title>Course TA Signups - TA Management System</title></head>
<body>
<div id="hajim_header" style="background-image:url(http://www.hajim.rochester.edu/assets/images/templates/header-background.png); width:960px;margin:auto">
<img alt="Hajim School of Engineering and Applied Sciences" src="//www.hajim.rochester.edu/assets/images/templates/header-logo.png" style="float:left;">
<a href="index.php">
<img alt="Department of Computer Science" src="//www.hajim.rochester.edu/assets/images/templates/csc-header-title.png" style="float:right;">
</a>
<br clear="all">
</div>

<h2>Course TA Signups</h2>
<div id = "main">

<?php

require_once('ta.php');
require_once('course.php');


if (!isset($_GET["crn"]) && (!isset($_GET["course"]) || !isset($_GET["department"]) || !isset($_GET["semester"]) || !isset($_GET["year"]))) {
?>
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
<a href=".">&lt;-- Back</a>
<?php
}
else {
	if (isset($_GET["crn"])) {
		$course = Course::getCourseByCrn($_GET["crn"]);
		$semester = $course->getSemester();
		$year = $course->getYear();
		$department = $course->getDepartment();
		$number = $course->getNumber();
	}
	else {
		$semester = $_GET["semester"];
		$year = $_GET["year"];
		$department = $_GET["department"];
		$number = $_GET["course"];	
	}
	#echo "<p>Semester:$semester</p>\n";
	#echo "<p>Year:$year</p>\n";
	#echo "<p>Department:$department</p>\n";
	#echo "<p>Name:$number</p>\n";
	try {
		$tas = TA::getByClass($year, $semester, $department, $number);
	} catch (TamsException $ex) {
	    echo "Failure: $ex<br/>";
	    $tas = array();
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
</div><div id = "footer"></div>

</body>
</html>

