<?php
require_once 'functions.php';
if(isset($_GET['user']))
{
    $user= $_GET['user'];
    $result = queryMysql("SELECT * FROM member WHERE username='$user'");
    $num = $result->num_rows;
    if($num)
    {
        echo "Ja existe uma conta com esse usuario.<br>";
    }

}
if(isset($_GET['email']))
{
    $email = sanit($_GET['email']);
    $result = queryMysql("SELECT * FROM member WHERE email='$email'");
    $num =$result->num_rows;
    if ($num)
    {
        echo "Ja existe uma conta cadastrada com esse email<br>";
    }
}
?>