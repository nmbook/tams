<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('instructor.php');
require_once('course.php');
require_once('session.php');

function handle_import($data, $as, $dt) {
    if ($as == 'instructors') {
        $instructors = explode("\n", trim($data));
        $instructors = array_map(function ($instructor) {
            $instructor = json_decode($instructor, true);
            return array(
                'netid'=>$instructor['netid'],
                'name'=>$instructor['name'],
                'email'=>$instructor['email'],
                'office_room'=>$instructor['office_room']
            );
        }, $instructors);
        $c = count($instructors);
        echo "<p>Inserting $c instructors...</p>\n";
        if (Instructor::import($instructors)) {
            echo "<p>Success!</p>\n";
        }
    } elseif ($as == 'courses') {
        $courses = explode("\n", trim($data));
        $sessions = array();
		$teaches = array();
        foreach ($courses as $val) {
            $course = json_decode($course, true);
			$crn = $course['crn'];
			foreach ($course['instructors'] as $instructor) {
				$teaches[] = array(
					'crn' => $crn,
					'instructor_id' => $instructor,
				);
			}
            foreach ($course['sessions'] as $session) {
                $sessions[] = array(
                    'crn'=>$crn,
                    'room'=>$session['room'],
                    'weekday'=>$session['weekday'],
                    'start_time'=>$session['start_time'],
                    'end_time'=>$session['end_time'],
                );
            }
        };
        $c = count($courses);
        echo "<p>Inserting $c courses...</p>\n";
        if (Course::import($courses)) {
            echo "<p>Success!</p>\n";
        }
        $c = count($sessions);
        echo "<p>Inserting $c course sessions...</p>\n";
        if (Session::import($sessions)) {
            echo "<p>Success!</p>\n";
        }
		$c = count($teaches);
		echo "<p>Inserting $c teaches relations...</p>\n";
		if (Instructor::importClasses($teaches)) {
			echo "<p>Success!</p>\n";
		}
    }
    //echo '<pre>'; print_r($courses);
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Data Importer Page</title>
</head>
<body>

<?php
// "as", the table data we are importing, currently hardcoded as "courses"
$as = isset($_POST['as']) ? $_POST['as'] : '';
// "dt", the imported datatype, currently hardcoded as "json", planned "csv" support
$dt = isset($_POST['dt']) ? $_POST['dt'] : '';
// "fdata", the file data
$fref = isset($_FILES['fdata']) ? $_FILES['fdata'] : null;
// "tdata", the post data alternative
$tdata = isset($_POST['tdata']) ? $_POST['tdata'] : null;

if ($tdata !== null) {
    handle_import($tdata, $as, $dt);
} elseif ($fref !== null) {
    //$target = 'temp_import_file';
    $fdata = file_get_contents($fref['tmp_name']);
    handle_import($fdata, $as, $dt);
} else {
?>
<h3>Import Instructor List (as JSON)</h3>
<form enctype="multipart/form-data" action="importer.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000"></input>
<input type="hidden" name="dt" value="json"></input>
<input type="hidden" name="as" value="instructors"></input>
<input type="file" name="fdata"></input><br />
<input type="submit" value="Import"></input>
</form>
<h3>Import Course List (as JSON)</h3>
<form enctype="multipart/form-data" action="importer.php" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000"></input>
<input type="hidden" name="dt" value="json"></input>
<input type="hidden" name="as" value="courses"></input>
<input type="file" name="fdata"></input><br />
<input type="submit" value="Import"></input>
</form>
<?php
}
?>

</body>
</html>
