<?php
require_once 'functions.php';

if(isset($_GET['like'])) // checkes if like is set
{ $like = sanit(decrypt($_GET['like']));   // decrypts like value and sanitizes it
    $post_id= sanit(decrypt($_GET['post_id'])); // decrypts the post id and sanitizes it
    $user=sanit(decrypt($_GET['user']));  // decrypts the user and sanitizes it

    $likee = queryMysql("SELECT likee FROM likes where user_like='$user' AND post_id='$post_id'"); // collects likee value for post and the logged in user
    if($likee == "") // checks if is empty
    {
            queryMysql("INSERT INTO likes(likee, post_id,user_like) VALUES('$like', '$post_id','$user')"); // if so adds value of like
    } else
        {
            queryMysql("UPDATE likes SET likee = 0 WHERE post_id='$post_id' AND user_like='$user'"); // else sets it to zero
            queryMysql("INSERT INTO likes(likee, post_id,user_like) VALUES('$like', '$post_id','$user')"); // and adds value in likee


            // need to include delete previous likes from data base
        }



}
?>