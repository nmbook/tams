<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('ta.php');
require_once('instructor.php');
require_once('utils.php');

date_default_timezone_set('America/New_York');
$this_year = intval(date('Y'));

$login_obj = Utils::getCurrentLogin();

$act = isset($_GET['act']) ? $_GET['act'] : '';

if ($act == 'login') {
    $lnetid = isset($_POST['netid']) ? $_POST['netid'] : '';
    $lpassword = isset($_POST['password']) ? $_POST['password'] : '';

    try {
        $ta_obj = TA::getByNetID($lnetid);
        $h_password = $ta_obj->getPassword();
        if (Utils::passwordVerify($lpassword, $h_password)) {
            setcookie('netid', $lnetid, time()+3600);
            setcookie('password', $h_password, time()+3600);
            $status = "Hello, you have logged in as {$ta_obj->getName()}!";
            $login_obj = $ta_obj;
        } else {
        	throw new TamsException(TamsException::E_GENERAL);
		}
    } catch (TamsException $ex) {
		try {
        	$prof_obj = Instructor::getByNetID($lnetid);
			echo "0";
        	$h_password = $prof_obj->getPassword();
        	if (Utils::passwordVerify($lpassword, $h_password)) {
				echo "1";
        	    setcookie('netid', $lnetid, time()+3600);
        	    setcookie('password', $h_password, time()+3600);
        	    $status = "Hello, you have logged in as {$prof_obj->getName()}!";
				echo "2";
        	    $login_obj = $prof_obj;
			}
			else {
        	    throw new TamsException(TamsException::E_GENERAL);
        	}
    	}
		catch (Exception $e) {
			$status = "Incorrect login";
		}
	}
} elseif ($act == 'create') {
    $netid = isset($_POST['netid']) ? $_POST['netid'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
    $fname = isset($_POST['fname']) ? $_POST['fname'] : '';
    $lname = isset($_POST['lname']) ? $_POST['lname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    if ($password != $password2) {
        $status = 'Your passwords do not match!';
        $create_failed = true;
    } else {
        try {
            TA::create($netid, "$fname $lname", $email, Utils::passwordCreate($password), $year);
            $ta_obj = TA::getByNetID($netid);
            $status = "You have successfully created an account, {$ta_obj->getName()}! Please log in.";
        } catch (TamsException $ex) {
            $status = 'Create failure: '.$ex;
            $create_failed = true;
        }
    }
} elseif ($act == 'logout') {
    setcookie('netid', '', time()-1);
    setcookie('password', '', time()-1);
    $login_obj = null;
}

?>
<!DOCTYPE html>
<html>
<head><title>TA Management System</title>
<link rel="stylesheet" type="text/css" href="front.css">
<script>
function TAlogin()
{
    document.getElementById("mainform").style.display = "block";
    document.getElementById("formplace").style.display = "none";
}
function createTA()
{
    document.getElementById("mainform").style.display = "none";
    document.getElementById("formplace").style.display = "block";
}

function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function removeCookies() {
	delete_cookie("netid");
	delete_cookie("password");
	location.reload();
}
<?php
// inject JavaScript to switch panes if create failed
if (isset($create_failed)) echo 'window.onload = createTA;';
?>


</script>


</head>
<body>
<div id="hajim_header" style="background-image:url(http://www.hajim.rochester.edu/assets/images/templates/header-background.png); width:960px;margin:auto">
<img alt="Hajim School of Engineering and Applied Sciences" src="//www.hajim.rochester.edu/assets/images/templates/header-logo.png" style="float:left;">
<a href="index.php">
<img alt="Department of Computer Science" src="//www.hajim.rochester.edu/assets/images/templates/csc-header-title.png" style="float:right;">
</a>
<br clear="all">

</div>
<h2 id="yellowtab" >TA Management System (Group: TuesdayNight) |
<?php
if ($login_obj != NULL) {
    echo "Hello, {$login_obj->getName()}!";
} else {
    echo 'Welcome';
}
?></h2>
<div id="main">
<?php if (isset($status)) { ?>
<div class="message"><?php echo $status; ?></div>
<?php } ?>
<?php if ($login_obj != NULL) { ?>
<h3>TA Management System Pages</h3>
<ul>
<?php if (get_class($login_obj) == 'TA') { ?>
<li><a href="ta-list.php">TA lister</a></li>
<li><a href="ta-list-for-course.php">TA lister by course</a></li>
<li><a href="apply-course.php">Apply for positions</a></li>
<?php } elseif (get_class($login_obj) == 'Instructor') { ?>
<li><a href="get-courses.php">Get courses</a></li>
<li><a href="set-professor.php">Set professor</a></li>
<?php } ?>
</ul>
<?php } else { ?>
<form id="mainform" action="index.php?act=login" method="post">
<h3>Login to the TA Management System</h3>
<table>
<tr>
<td align = "right">
<label for="netid">University NetID:</label> </td>
<td><input type="text" name="netid" id="netid" ></input>
</td>
</tr><tr>
<td align="right">
<label for="password">Password:</label></td>
<td> <input type="password" name="password" id="password" ></input></td></tr>
</table>
<input type="submit" value="login" id="login">
<button id="newTAbutton" type="button" onclick="createTA()">New TA</button>
</form>
<form id="formplace" action="index.php?act=create" method="post">
<h3>Create a New TA Account</h3>
<table>
<tr>
<td><label for="crfname">First name:</label></td><td>
<input type="text" name="fname" id="crfname"<?php if (isset($fname)) echo " value=\"$fname\""; ?>></input></td>
</tr><tr><td><label for="crlname">Last name:</label></td><td>
<input type="text" name="lname" id="crlname"<?php if (isset($lname)) echo " value=\"$lname\""; ?>></input></td>
</tr><tr><td><label for="crnetid">University NetID:</label></td><td>
<input type="text" name="netid" id="crnetid"<?php if (isset($netid)) echo " value=\"$netid\""; ?>></input></td>
</tr><tr><td><label for="crpassword">Password:</label></td><td>
<input type="password" name="password" id="crpassword"></input></td>
</tr><tr><td colspan="2" style="color:red;font-weight:bold">Warning: Password is stored in plain text for demo. DO NOT USE A REAL PASSWORD!!!</td>
</tr><tr><td><label for="crpassword2">Confirm password:</label></td><td>
<input type="password" name="password2" id="crpassword2"></input></td>
</tr><tr><td><label for="cremail">E-mail:</label></td><td>
<input type="email" name="email" id="cremail"<?php if (isset($email)) echo " value=\"$email\""; ?>></input></td>
</tr><tr><td><label for="cryear">Class year:</label></td><td>
<select name="year" id="cryear">
<?php for ($year = $this_year - 2; $year < $this_year + 6; $year++) { ?>
<option value="<?php echo $year; ?>"<?php if ($year == $this_year) echo ' selected="selected"'; ?>><?php echo $year; ?></option>
<?php } ?></select></td>
</tr>
</table>

<input type="submit" value="create" id="login">
<button id= "Talogin" type="button" onclick="TAlogin()">Back to Login </button>
</form>

</div>
<button id="Logout" type="button" onclick="removeCookies()">Logout</button>
<?php } ?>
</div>
<div id="footer">
</div>
</body>
</html>
