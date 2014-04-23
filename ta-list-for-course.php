<?php
require_once('../dbsetup.php');
require_once('utils.php');
function getTas() {

 $stmt = $db->prepare('SELECT netid,name,email,class_year FROM courses c
						INNER JOIN course_apps ca ON c.crn = ca.crn
						INNER JOIN tas t ca ON t.netid = ca.netid
						WHERE ca.state = "approved" AND c.year = 2014 AND c.semester = "fall"
						AND c.department = "CSC" AND course_number = 173;',
            array(),
            function ($x) { return new TA($x); });
    }
?>
<!DOCTYPE html>
<html>
<head><title>TA Listed for a 173 (BETAWEB)</title></head>
<body>
<h1>TuesdayNight on Betaweb</h1>
<h2>TA Lister</h2>
<?php
 echo '<table cellspacing="1"><thead><th width="150">Net ID</th><th width="150">Name</th><th width="250">E-Mail</th><th>Class Year</th></thead><tbody>';
$tas = getTas();
    foreach ($tas as $ta) {
        echo "<tr><td>{$ta->getNetID()}</td><td>{$ta->getName()}</td><td>{$ta->getEmail()}</td><td>{$ta->getClassYear()}</td></tr>";
    }
    echo '</tbody></table>';

?>


</body>
</html>

