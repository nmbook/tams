<!DOCTYPE html>
<html>
<head><title>Set the professor teaching a course (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>Professor update</h2>
<?php
    require_once('instructor.php');
    if (!isset($_GET['netid']) || !isset($_GET['crn'])) {
?>
<p> Would you like to look up the courses for another proffesor?</p>
<form action="set-courses.php" method="get">
Netid: <input type="text" name="netid"  placeholder="<?php echo $netid?>" ><br>
CRN: <input type= "text" name= "crn" placeholder="<?php echo $crn?>"><br>

<input type="submit">
</form>
<?php
    }
    else {
		$netid = $_GET['netid'];
        $professor = Instructor::GetByNetid($netid);
		
        $crn = $_GET['crn'];
        $courses = $professor::assignCourse($crn);
    }
?>
</body>
</html>


