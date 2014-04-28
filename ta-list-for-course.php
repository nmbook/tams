<?php
require_once('ta.php');

$class = $_GET["course"];

?>
<!DOCTYPE html>
<html>
<head><title>TA Lister by Course - TA Management System</title></head>
<body>
<h1>TA Management System on Betaweb</h1>
<h2>TA Lister by Course</h2>
<p> Would you like to look up the TAs for another CSC course?</p>
<form action="ta-list-for-course.php" method="get">
Course: <input type="text" name="course"  placeholder="<?php echo $class?>" ><br>
<input type="submit">

<?php

echo $class;
 echo '<table cellspacing="1"><thead><th width="150">Net ID</th><th width="150">Name</th><th width="250">E-Mail</th><th>Class Year</th></thead><tbody>';
$tas = TA::getByclass($class);
    foreach ($tas as $ta) {
        echo "<tr><td>{$ta->getNetID()}</td><td>{$ta->getName()}</td><td>{$ta->getEmail()}</td><td>{$ta->getClassYear()}</td></tr>";
    }
    echo '</tbody></table>';

?>
<a href=".">&lt;-- Back</a>
</body>
</html>

