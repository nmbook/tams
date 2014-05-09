<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('ta.php');
require_once('utils.php');

$login_obj = Utils::getCurrentLogin();

$act = isset($_GET['act']) ? $_GET['act'] : '';

if ($act == 'login') {
    $netid = isset($_POST['netid']) ? $_POST['netid'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    try {
        $ta_obj = TA::getByNetID($netid);
        $h_password = $ta_obj->getPassword();
        if (Utils::passwordVerify($password, $h_password)) {
            setcookie('netid', $netid, time()+3600);
            setcookie('password', $h_password, time()+3600);
            $status = "Hello, you have logged in as {$ta_obj->getName()}!";
            $login_obj = $ta_obj;
        } else {
            $status = 'Login failure: Username or password is incorrect.';
        }
    } catch (TamsException $ex) {
        $status = 'Login failure: Username or password is incorrect.';
    }
} elseif ($act == 'create') {
    $netid = isset($_POST['netid']) ? $_POST['netid'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $fname = isset($_POST['fname']) ? $_POST['fname'] : '';
    $lname = isset($_POST['lname']) ? $_POST['lname'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';

    try {
        TA::create($netid, "$fname $lname", $email, Utils::passwordCreate($password), $year);
        $ta_obj = TA::getByNetID($netid);
        $status = "You have created an account, {$ta_obj->getName()}!";
    } catch (TamsException $ex) {
        $status = 'Create failure: '.$ex;
    }
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
    //document.getElementById("newTAbutton").style.display = "none";
    /*
    var f = document.createElement("newTA");
    f.setAttribute('method',"post");
    f.setAttribute('action',"submit.php");

    var i = document.createElement("input"); //input element, text
    i.setAttribute('type',"text");
    i.setAttribute('name',"username");

    var s = document.createElement("input"); //input element, Submit button
    s.setAttribute('type',"submit");
    s.setAttribute('value',"Submit");

    f.appendChild(i);
    f.appendChild(s);

    document.getElementById('formplace').appendChild(f);
    //var i1 = document.createElement("input");
    //var iocument.createElement("input");
    //document.createElement("button");
    */
}


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
<form id="mainform" action="index.php?act=login" method="post">
<h3>Login to the TA Management System</h3>
<table>
<tr>
<td align = "right">
<label for="netid">Netid:</label> </td>
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
<td>First Name:</td><td> <input type="text" name="fname" ></td>
</tr><tr><td>Last Name:</td><td> <input type="text" name="lname" ></td>
</tr><tr><td>Netid:</td><td> <input type="text" name="netid" ></td>
</tr><tr><td>Password:</td><td> <input type="password" name="password" ></td>
</tr><tr><td>E-Mail:</td><td> <input type="email" name="email" ></td>
</tr><tr><td>Class Year:</td><td> <input type="text" name="year" ></td>
</tr>
</table>

<input type="submit" value="create" id="login">
<button id= "Talogin" type="button" onclick="TAlogin()">Back to Login </button>
</form>

</div>
<div id="footer"></div>
</body>
</html>
