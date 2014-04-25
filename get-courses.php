<?php
require_once('course.php');

$class = $_GET["course"];
$year = $_GET["year"];
$term = $_GET["term"];

?>
<!DOCTYPE html>
<html>
<head><title>Course Taught by a Professor (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>TA Lister</h2>
<p> Would you like to look up the TAs for another CSC course?</p>
<form action="get-courses.php" method="get">
Course: <input type="text" name="course"  placeholder="<?php echo $class?>" ><br>
Term: <input type="text" name="term"  placeholder="<?php echo $term?>" ><br>
Year: <input type="text" name="year"  placeholder="<?php echo $year?>" ><br>


<input type="submit">

<?php

echo $class;
 echo '<table cellspacing="1"><thead><th width="150">Net ID</th><th width="150">Name</th><th width="250">E-Mail</th><th>Class Year</th></thead><tbody>';
$courses = COURSE::getByCoursesNetid($class);
    foreach ($courses as $course) {
        echo "<tr><td>{$course->getNetID()}</td><td>{$->getName()}</td><td>{$ta->getEmail()}</td><td>{$ta->getClassYear()}</td></tr>";
    }
    echo '</tbody></table>';

?>


</body>
</html>

