<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('utils.php');
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
        $success = true;
        try {
            $c = count($instructors);
            echo "<p>Inserting $c instructors...</p>\n";
            Utils::beginTransaction();
            Instructor::import($instructors);
        } catch (TamsException $ex) {
            $success = false;
            echo "<p>Failure: $ex</p>\n";
            Utils::cancelTransaction();
        }
        if ($success) {
            Utils::commitTransaction();
            echo "<p>Success!</p>\n";
        }
    } elseif ($as == 'courses') {
        $courses = explode("\n", trim($data));
        $sessions = array();
		$teaches = array();
        $courses = array_map(function ($val) use (&$teaches,&$sessions) {
            $course = json_decode($val, true);
			$crn = $course['crn'];
			foreach ($course['instructors'] as $instructor) {
				$teaches[] = array(
					'crn' => $crn,
					'netid' => $instructor,
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
			unset($course['instructors']);
			unset($course['sessions']);
			return $course;
        },$courses);
        $success = true;
        try {
            $c = count($courses);
            echo "<p>Inserting $c courses...</p>\n";
            Utils::beginTransaction();
            Course::import($courses);
            
            $c = count($sessions);
            echo "<p>Inserting $c course sessions...</p>\n";
            Session::import($sessions);

            $c = count($teaches);
            echo "<p>Inserting $c teaches relations...</p>\n";
            Instructor::importClasses($teaches);
        } catch (TamsException $ex) {
            $success = false;
            echo "<p>Failure: $ex</p>\n";
            Utils::cancelTransaction();
        }
        if ($success) {
            Utils::commitTransaction();
            echo "<p>Success!</p>\n";
        }
    }
    //echo '<pre>'; print_r($courses);
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Data Importer Page - TA Management System</title>
</head>
<body>

<h1>TA Management System on Betaweb</h1>
<h2>Data Import Page</h2>

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
}

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
<a href=".">&lt-- Back</a>
</body>
</html>
