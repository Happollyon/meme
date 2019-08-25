<?php
require_once 'functions.php';
if(isset($_GET['delete_comment'])) // checks if comment to be deleted is set
{

    $del_comment = sanit($_GET['delete_comment']); // sanitizes value
   queryMysql("DELETE FROM comments_likes WHERE comment_id='$del_comment'"); // deletes all likes from comment

    $query = "DELETE FROM comments  WHERE comment_id ='$del_comment'"; // deletes comment

    queryMysql($query);

}

?>