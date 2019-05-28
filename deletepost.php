<?php
require_once 'functions.php';
if(isset($_GET['delete'])) // checks if delete is set
{
    $post_id=sanit(decrypt($_GET['delete']));  // decrypts data and sanitizes it

    queryMysql("DELETE FROM posts WHERE post_id='$post_id'"); // deletes it from database
    //need to include delete for all the likes form data base
}
?>