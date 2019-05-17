<?php
require_once 'functions.php';

session_start();
if(isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    $loggedin= TRUE;

}else $loggedin=FALSE;
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>meme.com.br</title>
   <link rel="stylesheet" href="header.css">
</head>

<body>
<?php
if(!$loggedin)
{
    echo "<div id='nave-menu'>
            <ul>   <a href='login.php'><li id='login-btn'>login</li></a>
            <a href='register.php'><li id='sign-btn'>signup</li></a>
         </ul></div>";

}
else{
    echo"<div id='nave-menu'>
        <ul>    <li id='hot-btn'> hot </li>
        <li id='perfil-btn'> perfil </li>
        <a href='exit.php'><li id='sair-btn'> sair </li></a></ul>
        </div>";
}
?>

