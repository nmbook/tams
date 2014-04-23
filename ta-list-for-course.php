<?php
require_once('ta.php');

$class = $_POST["course"];

?>
<!DOCTYPE html>
<html>
<head><title>TA Listed for a 173 (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>TA Lister</h2>
<p> Would you like to look up the TAs for another CSC course?</p>
<form action="ta-list-for-course.php" method="post">
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


</body>
</html>

