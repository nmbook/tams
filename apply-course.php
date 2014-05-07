<!DOCTYPE html>
<html>
<head><title>Apply to a Course - TA Management System</title></head>
<body>
<h1>TA Management System on Betaweb</h1>
<h2>Apply to a course</h2>
<?php
require_once('course.php');
require_once('ta.php');
if (!isset($_POST['netid']) || !isset($_POST['for_credit']) ||
    (!isset($_POST['crn']) && !(isset($_POST['dept']) && isset($_POST['number']) && isset($_POST['year']) && isset($_POST['semester'])))) {
?>
    <form action="apply-course.php" method="post">
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
    $netid = $_POST['netid'];
    $for_credit = $_POST['for_credit'];
	$guard = false;
    try {
        $ta = TA::getByNetID($netid);
    }
    catch (Exception $e) {
        echo "<p>ERROR: a TA with netid $netid is not in our database</p>\n";
		$guard = true;
    }
    if (isset($_POST['crn'])) {
        $crn = $_POST['crn'];
    }
    else {
		$dept = $_POST['dept'];
		$number= $_POST['number'];
		$year = $_POST['year'];
		$semester = $_POST['semester'];
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

