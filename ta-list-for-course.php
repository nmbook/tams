<?php

require_once('ta.php');

$class = $_GET["course"];

?>
<!DOCTYPE html>
<html>
<head><title>Course TA Signups - TA Management System</title></head>
<body>
<h1>TA Management System on Betaweb</h1>
<h2>Course TA Signups</h2>
<p> Would you like to look up the TAs for a course?</p>
<form action="ta-list-for-course.php" method="get">
<!-- TODO: add the other fields, semester, year, department? -->
<label for="course">Course number:</label>
<input type="text" id="course" name="course" placeholder="ex. 173" value="<?php echo $class?>" ></input><br/>
<input type="submit"></input>
</form>

<?php

try {
    $tas = TA::getByClass('fall', '2014', 'CSC', $class);
} catch (TamsException $ex) {
    echo "Failure: $ex<br/>";
    $tas = array();
}
if (count($tas) > 0) {
    echo "<p>Showing TAs signed up to TA $class:</p>";
    echo '<table cellspacing="1"><thead><th width="150">Net ID</th><th width="150">Name</th><th width="250">E-Mail</th><th>Class Year</th></thead><tbody>';
    foreach ($tas as $ta) {
        echo "<tr><td>{$ta->getNetID()}</td><td>{$ta->getName()}</td><td>{$ta->getEmail()}</td><td>{$ta->getClassYear()}</td></tr>";
    }
    echo '</tbody></table>';
}

?>

<a href=".">&lt;-- Back</a>
</body>
</html>

