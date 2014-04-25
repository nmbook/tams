<?php
require_once('course.php');
$year = $_GET["year"];
$class = $_GET["course"];

?>
<!DOCTYPE html>
<html>
<head><title>Course Taught by a Professor (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>Course Lister</h2>
<p> Would you like to look up the courses for another proffesor?</p>
<p><?php echo $year; echo $class?> </p>
<form action="get-courses.php" method="get">
Course: <input type="text" name="course"  placeholder="<?php echo $class?>" ><br>
Year: <input type="text" name="year"  placeholder="<?php echo $year?>" ><br>


<input type="submit">
</form>
<?php


 echo '<table cellspacing="1"><thead><th width="150">CRN</th><th width="150">Year</th><th width="250">Semester</th><th>Dept</th></thead><tbody>';
$courses = COURSE::getCoursesByNetid($class, $year);
    foreach ($courses as $course) {
        echo "<tr><td>{$course->getCrn()}</td><td>{$course->getYear()}</td><td>{$course->getSemester()}</td><td>{$course->getDepartment()}</td></tr>";
    }
    echo '</tbody></table>';

?>
<p>
<?php echo $courses?>
</p>
</body>
</html>

