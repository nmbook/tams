<?php

?>
<!DOCTYPE html>
<html>
<head><title>TA Signup - TA Management System</title></head>
<style>
div.content {
    width: 860px;
    margin: 0 auto;
}

form {
    width: 840px;
    margin: 2px auto;
}

label, input {
    display: inline-block;
    margin-bottom: 3px;
}

label {
    width: 30%;
    text-align: right;
}

label + input {
    width: 30%;
    margin: 0 30% 0 4%;
}
</style>
<body>
<div class="content">
<h3>Sign Up to Become a TA</h3>
<form action="ta_signup.php" method="post">
<label for="netid">University NetID:</label>
<input type="text" name="netid" id="netid"></input><br />

<label for="name">Name:</label>
<input type="text" name="name" id="name"></input><br />

<label for="email">E-mail:</label>
<input type="email" name="email" id="email"></input><br />

<label for="password">:</label>
<input type="password" name="password" id="password" disabled></input><br />

<input type="submit" value="Sign Up"></input>
</form>
</div>
<a href=".">&lt-- Back</a>
</body>
</html>

