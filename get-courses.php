<!DOCTYPE html>
<html>
<head><title>Course Taught by a Professor (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>Course Lister</h2>
<?php
	require_once('course.php');
	if (!isset($_GET['netid']) || !isset($_GET['year'])) {
?>
<p> Would you like to look up the courses for another proffesor?</p>
<form action="get-courses.php" method="get">
Netid: <input type="text" name="netid"  placeholder="<?php echo $netid?>" ><br>
Year: <input type="text" name="year"  placeholder="<?php echo $year?>" ><br>


<input type="submit">
</form>
<?php
	}
	else {
		$netid = $_GET['netid'];
		$year = $_GET['year'];
		echo '<table cellspacing="1"><thead><th width ='100'> CRN </th><th width="300">Course Name</th><th width="50">Day</th><th width="150">Time</th><th>Room</th></thead><tbody>';
		$courses = Course::getCoursesByNetid($netid, $year);
		foreach ($courses as $course) {
			echo "<tr><td>{$course['crn']}</td><td>{$course['name']}</td><td>{$course['weekday']}</td><td>{$course['start_time']}</td><td>{$course['room']}</td></tr>";
		}
		echo '</tbody></table>';
	}
?>
</body>
</html>

