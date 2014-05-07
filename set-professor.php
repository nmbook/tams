<!DOCTYPE html>
<html>
<head><title>Set the professor teaching a course (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>Professor update</h2>
<?php
require_once('instructor.php');
require_once('course.php');    
if (!isset($_POST['netid']) || !isset($_POST['crn'])) {
?>
<p> Would you like to look up the courses for another professor?</p>
<form action="set-professor.php" method="post">
Netid: <input type="text" name="netid"  placeholder="<?php echo $netid?>" ><br>
CRN: <input type= "text" name= "crn" placeholder="<?php echo $crn?>"><br>
<input type="submit">
</form>
<?php
    }
    else {
		$guard = true;
		$netid = $_POST['netid'];
		$crn = $_POST['crn'];
		try {
        	$professor = Instructor::GetByNetid($netid);
		}
		catch (Exception $e) {
			echo "<p>ERROR: there is no instructor $netid</p>\n";
			$guard = false;
		}
		if ($guard) {
			try {
				$course = Course::getCourseByCrn($crn);
			}
			catch (Exception $e) {
				echo "<p>ERROR: there is no course with crn $crn</p>\n";
				$guard = false;
			}
		}
		if ($guard) {
			try {
        		$professor->assignCourse($crn);
    		}
			catch (Exception $e) {
				echo "<p>ERROR: Assignment failed. Perhaps course $crn doesn't exist? {$e->getMessage()}</p>\n";
				$guard = false;
			}
		}
		if ($guard) {
			echo "<p>$netid has been sucessfully assigned to $crn</p>\n";
		}
	}
?>
</body>
</html>


