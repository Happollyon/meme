<?php
session_start();
unset($_SESSION['user']);
unset($_SESSION['pass']);
session_destroy();
global $loggedin;
$loggedin= FALSE;
header('location:http://localhost/meme/login.php');

exit();
?>