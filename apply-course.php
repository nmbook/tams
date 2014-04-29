<!DOCTYPE html>
<html>
<head><title>Apply to a Course - TA Management System</title></head>
<body>
<h1>TA Management System on Betaweb</h1>
<h2>Apply to a course</h2>
<?php
require_once('course.php');
require_once('ta.php');
if (!isset($_GET['netid']) || !isset($_GET['for_credit']) ||
    (!isset($_GET['crn']) && !(isset($_GET['dept']) && isset($_GET['number']) && isset($_GET['year']) && isset($_GET['semester'])))) {
?>
    <form action="apply-course.php" method="get">
    <label for="netid">Netid:</label>
    <input type="text" name="netid" id="netid"></input><br/>
    <label for="for_credit">For Credit?:</label>
    <select name="for_credit" id="for_credit"><option value="1">Yes</option><option value="0">No</option></select><br/>
    <label for="dept">Course Department:</label>
    <input type="text" name="dept" id="dept"></input><br/>
    <label for="number">Course Number:</label>
    <input type="text" name="number" id="number"></input><br/>
    <label for="year">Year:</label>
    <input type="text" name="year" id="year"></input><br/>
    <label for="semester">Semester:</label>
    <input type="text" name="semester" id="semester"></input><br/>
    <input type="submit"></input>
    </form>
<?php
} else {
    $netid = $_GET['netid'];
    $for_credit = $_GET['for_credit'];
	$guard = false;
    try {
        $ta = TA::getByNetID($netid);
    }
    catch (Exception $e) {
        echo "<p>ERROR: a TA with netid $netid is not in our database</p>\n";
		$guard = true;
    }
    if (isset($_GET['crn'])) {
        $crn = $_GET['crn'];
    }
    else {
		$dept = $_GET['dept'];
		$number= $_GET['number'];
		$year = $_GET['year'];
		$semester = $_GET['semester'];
        try {
            $crn = Course::getCourseByName($dept,$number,$year,$semester)->getCrn();	
        }
        catch (Exception $e) {
			echo "<p>ERROR: there is no $dept $number class in $semester $year</p>\n";
			$guard = true;
        }
    }
	if (!$guard) {
		try {
        	$ta->applyCourse($crn,$for_credit);
			echo "<p>Course application sucessful!</p>\n";
		}
		catch (Exception $e) {
			echo "<p>ERROR: course application failed. Perhaps you already applied for this course?</p>\n";	
		}
	}
}
?>
<a href=".">&lt-- Back</a>
</body>
</html>

