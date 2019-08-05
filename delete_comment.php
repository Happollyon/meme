<?php
require_once 'functions.php';
if(isset($_GET['delete_comment']))
{

    $del_comment = sanit($_GET['delete_comment']);
   queryMysql("DELETE FROM comments_likes WHERE comment_id='$del_comment'");

    $query = "DELETE FROM comments  WHERE comment_id ='$del_comment'";

    queryMysql($query);

}

?>