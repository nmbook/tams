<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('ta.php');

function is_valid($regex, $value, $error = 'generic value') {
    if (strlen($value) == 0) {
        throw new Exception("The $error field cannot be left blank.");
    }
    $result = preg_match($regex, $value);
    if ($result === false) {
        throw new Exception('Regex error');
    } elseif (!$result) {
        throw new Exception("You must enter a valid $error.");
    } else {
        return true;
    }
}

function is_valid_netid($netid) {
    return is_valid('/[A-Za-z0-9]{1,8}/', $netid, 'NetID');
}

function is_valid_name($name) {
    return is_valid('/[A-Za-z-\' ]{1,32}/', $name, 'name');
}

function is_valid_email($email) {
    return is_valid('/[A-Za-z0-9@.]{3,256}/', $email, 'e-mail address');
}

function is_valid_year($year) {
    return is_valid('/[0-9]{4}/', $year, 'class year');
}

function create_account($netid, $name, $email, $type, $year = null, $office = null) {
    
}

?>
<!DOCTYPE html>
<html>
<head><title>TA Account Create - TA Management System</title></head>
<style>
div.content {
    width: 860px;
    margin: 0 auto;
}

form {
    width: 840px;
    margin: 2px auto;
}

label, input, select {
    display: inline-block;
    margin-bottom: 3px;
}

label {
    width: 30%;
    text-align: right;
}

label + input, label + select {
    width: 30%;
    margin: 0 30% 0 4%;
}
</style>
<body>
<div class="content">
<h3>Sign Up to Become a TA</h3>
<?php

$submit = isset($_GET['submit']) ? $_GET['submit'] : 0;
$netid = isset($_POST['netid']) ? $_POST['netid'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$class = isset($_POST['class']) ? $_POST['class'] : 2000;
$show_form = true;
if ($submit) {
    try {
        if (is_valid_netid($netid) && is_valid_email($email) &&
            is_valid_name($name) && is_valid_year($class)) {
            create_account($netid, $name, $email, 'student', $class);
            $show_form = false;
        }
    } catch (Exception $e) {
        echo '<p>Error with account create: '.$e->getMessage().'</p>';
    }
}

if ($show_form) {
?>
<form action="ta-create.php?submit=1" method="post">
<label for="netid">University NetID:</label>
<input type="text" name="netid" id="netid"></input><br />

<label for="name">Name:</label>
<input type="text" name="name" id="name"></input><br />

<label for="email">E-mail:</label>
<input type="email" name="email" id="email"></input><br />

<label for="class">Class year:</label>
<select name="class" id="class">
<?php
$yr = date('Y');
for ($yri = $yr - 2; $yri < $yr + 6; $yri++) {
    $yrs = ($yr == $yri) ? ' selected="selected"' : '';
    echo "<option value=\"$yri\"$yrs>$yri</option>\n";
}
?>
</select><br />

<label for="password">Password:</label>
<input type="password" name="password" id="password" disabled></input><br />

<input type="submit" value="Sign Up"></input>
</form>
<?php
}
?>
</div>
<a href=".">&lt-- Back</a>
</body>
</html>

