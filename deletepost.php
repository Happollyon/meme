<?php
require_once 'functions.php';
if(isset($_GET['delete'])) // checks if delete is set
{

    $post_id=sanit($_GET['delete']);  // decrypts data and sanitizes it
    $result = queryMysql("SELECT comment_id FROM comments WHERE post_id = '$post_id'"); // selects comments owned by the post
    $num = $result->num_rows;
    for($i =0;$i<$num; $i++)
    {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        queryMysql("DELETE FROM comments_likes WHERE comment_id='" .$row['comment_id']."'"); //deletes all likes owned by comments

    }
    queryMysql("DELETE FROM comments WHERE post_id='$post_id'"); // deletes all likes owned bxy post
    queryMysql("DELETE FROM likes WHERE post_id='$post_id'"); // deletes all likes owned bxy post
    queryMysql("DELETE FROM posts WHERE post_id='$post_id'"); // deletes it from database

    /*
      delete all likes owned by comment
     */

    //need to include delete for all the likes form data base
}
?>