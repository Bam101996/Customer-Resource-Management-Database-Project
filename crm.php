<html>
<head>
<title>Print Anything Division Customer Management</title>
</head>
<?php
session_start();
if ($_SESSION['username']){
    session_destroy();
}
?>
<body>

<h1>Print Anything Division Customer Management</h1><br>
<h2>We add a new dimension to printing!</h2>

<h2>Sign in:</h2><br>

<FORM action ="home.php" method = "post">
    Username: <input type = "text" name = "username_login"><br><br>
    Password: <input type = "password" name = "pass_login"><br><br>
    <input type = "submit" value = "Log in">
</FORM>
</body>
</html>