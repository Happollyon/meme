<?php

// checks if there is a user with this user name or email
require_once 'functions.php';
if(isset($_GET['user'])) // checks if user is set in the request
{
    $user= sanit($_GET['user']);                // sanitizes the value
    $result = queryMysql("SELECT * FROM member WHERE username='$user'"); // checks the database
    $num = $result->num_rows;
    if($num) // if there is a result
    {
        echo "<div id='error'>Ja existe uma conta com esse usuario.</div><br>";// prints this on the screen
        die(); // same as exit
    }

}
if(isset($_GET['email'])) // checks if email is set in the request
{
    $email = sanit($_GET['email']);// sanitizes the value
    $result = queryMysql("SELECT * FROM member WHERE email='$email'");// checks the database
    $num =$result->num_rows;
    if ($num) // if there is a result
    {
        echo "<div id='error'>Ja existe uma conta cadastrada com esse email</div><br>";// prints this on the screen
        die(); // same as exit
    }
}
?>