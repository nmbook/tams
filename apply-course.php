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
    echo "break 1<br>\n";
    $netid = $_GET['netid'];
    $for_credit = $_GET['for_credit'];
    echo "break 2<br>\n";
    try {
        $ta = TA::getByNetID($netid);
    }
    catch (Exception $e) {
        echo 'Caught Exception1: ', $e->getMessage(), "\n";
    }
    echo "break 3<br>\n";
    if (isset($_GET['crn'])) {
        echo "break 4<br>\n";
        $crn = $_GET['crn'];
    }
    else {
        echo "break 5<br>\n";
        try {
            $crn = Course::getCourseByName($_GET['dept'],$_GET['number'],$_GET['year'],$_GET['semester'])->getCrn();	
        }
        catch (Exception $e) {
            echo 'Caught Exception2: ', $e->getMessage(), "\n";
        }
    }
    echo "break 6<br>\n";
    try {
        $ta->applyCourse($crn,$for_credit);
    }
    catch (Exception $e) {
        echo 'Caught Exception3: ', $e->getMessage(), "\n";	
    }
    echo "break 7<br>\n";
}
?>
<a href=".">&lt-- Back</a>
</body>
</html>

