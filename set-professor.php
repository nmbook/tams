<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="front.css">
<title>Set the professor teaching a course (BETAWEB)</title></head>
<body>
<div id="hajim_header" style="background-image:url(http://www.hajim.rochester.edu/assets/images/templates/header-background.png); width:960px;margin:auto">
<img alt="Hajim School of Engineering and Applied Sciences" src="//www.hajim.rochester.edu/assets/images/templates/header-logo.png" style="float:left;">
<a href="index.php">
<img alt="Department of Computer Science" src="//www.hajim.rochester.edu/assets/images/templates/csc-header-title.png" style="float:right;">
</a>
<br clear="all">
</div>

<h2>Professor update</h2>
<div id = "main">
<?php
require_once('instructor.php');
require_once('course.php');    
if (!isset($_POST['netid']) || !isset($_POST['crn'])) {
?>
<p> Would you like to look up the courses for another professor?</p>
<form action="set-professor.php" method="post">
Netid: <input type="text" name="netid"  placeholder="<?php echo $netid?>" ><br>
CRN: <input type= "text" name= "crn" placeholder="<?php echo $crn?>"><br>
<input type="submit">
</form>
<?php
    }
    else {
		$guard = true;
		$netid = $_POST['netid'];
		$crn = $_POST['crn'];
		try {
        	$professor = Instructor::GetByNetid($netid);
		}
		catch (Exception $e) {
			echo "<p>ERROR: there is no instructor $netid</p>\n";
			$guard = false;
		}
		if ($guard) {
			try {
				$course = Course::getCourseByCrn($crn);
			}
			catch (Exception $e) {
				echo "<p>ERROR: there is no course with crn $crn</p>\n";
				$guard = false;
			}
		}
		if ($guard) {
			try {
        		$professor->assignCourse($crn);
    		}
			catch (Exception $e) {
				echo "<p>ERROR: Assignment failed. Perhaps course $crn doesn't exist? {$e->getMessage()}</p>\n";
				$guard = false;
			}
		}
		if ($guard) {
			echo "<p>$netid has been sucessfully assigned to $crn</p>\n";
		}
	}
?>
</div><div id = "footer"></div>
</body>
</html>


