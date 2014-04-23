<?php
require_once('ta.php');


?>
<!DOCTYPE html>
<html>
<head><title>TA Listed for a 173 (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>TA Lister</h2>
<form action="welcome.php" method="post">
Name: <input type="text" name="name"><br>
E-mail: <input type="text" name="email"><br>
<input type="submit">
</form>

<?php

$class = $_POST["course"];
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

