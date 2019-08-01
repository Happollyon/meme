<?php

// this code receives an ajax request  passing user and profile
require_once 'functions.php';
$profile="";
$user = "";
$unfollw_profile= "";

if(isset($_GET['profile'])&&isset($_GET['user'])) // checks if both values are set following code
{
    $user = sanit($_GET['user']);  // sanitizes  and stores in variable
    $profile = sanit($_GET['profile']); // sanitizes and stores in variable
    $query = "INSERT INTO friends(user, following) VALUES('$user', '$profile')";  // query is prepared
    queryMysql($query); // query is sent to database
}

if(isset($_GET['user'])&& isset($_GET['unfollow_profile'])) // if unfollow is set
{
    $user = sanit($_GET['user']); // sanitizes  and stores in variable
    $profile = sanit($_GET['unfollow_profile']);// sanitizes  and stores in variable
    $query = "DELETE FROM friends WHERE user='$user' and following = '$profile'"; // query is prepared
    queryMysql($query); // query is sent to database
}
$result3 = queryMysql("SELECT user FROM friends WHERE following='$profile'");// select people following the user
$num3 = $result3->num_rows;// selects numbers of rows of people following user

$result2 = queryMysql(("SELECT following FROM friends WHERE user='$$profile'")); // selects people user is following
$num2= $result2->num_rows;// selects numbers of rows  of people user is following

$myarray = array('following'=>$num2,'followers'=>$num3) ; // array is prepared
echo json_encode($myarray);  // jason file is prepared and sent

?>