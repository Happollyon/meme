<?php
$dbhost = "localhost";
$dbname = "meme";
$dbuser = "root";
$dbpass="" ;

$connection = new mysqli($dbhost, $dbuser,$dbpass , $dbname);
if($connection->error) die($connection->connect_error);


function queryMysql($query)
{
    global $connection;
    $result = $connection->query($query);
    if(!$result) die($connection->error);
    return $result;
}

function destroSection()
{
    $_SESSION=array();
    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(),'',time()-2592000,'/');
    session_destroy();
}

function sanit($var)
{
    global $connection;
    $var =strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}
?>