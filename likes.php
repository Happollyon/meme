<?php
require_once 'functions.php';

if(isset($_GET['like'])) // checkes if like is set
{
    $like = sanit(decrypt($_GET['like']));  // decrypts like value and sanitizes it
    $post_id = sanit(decrypt($_GET['post_id'])); // decrypts the post id and sanitizes it
    $user = sanit(decrypt($_GET['user']));  // decrypts the user and sanitizes it


    $likee = queryMysql("SELECT likee FROM likes where user_like='$user' AND post_id='$post_id'"); // collects likee value for post and the logged in user
    if ($likee == "") // checks if is empty
    {
        queryMysql("INSERT INTO likes(likee, post_id,user_like) VALUES('$like', '$post_id','$user')"); // if so adds value of like
    } else {
        queryMysql("UPDATE likes SET likee = 0 WHERE post_id='$post_id' AND user_like='$user'"); // else sets it to zero
        queryMysql("INSERT INTO likes(likee, post_id,user_like) VALUES('$like', '$post_id','$user')"); // and adds value in likee


        // need to include delete previous likes from data base
    }
    $query="SELECT text, user, post_id, DATEDIFF(NOW(),post_date) from posts ORDER BY post_date DESC";

      // encrypts data

    // collecting sum of likes
    $query= "SELECT SUM(likee) FROM likes WHERE post_id='$post_id' AND likee=1";
    $result2 =queryMysql($query);
    $row2 = $result2->fetch_array(MYSQLI_ASSOC); // stores likes in array

    // collecting sum o dislikes
    $query= "SELECT SUM(likee) FROM likes WHERE post_id='$post_id' AND likee=-1";
    $result3 =queryMysql($query);
    $row3= $result3->fetch_array(MYSQLI_ASSOC); // stores dislikes in array


    $likes = $row2['SUM(likee)']; // sum of likes
    $dislikes= $row3['SUM(likee)']; // sum of dislikes
    $post_id_ecrypted = encrypt($post_id);
    //echo $post_id_ecrypted;
    $myarray = array('likes'=>$likes,'dislikes'=>$dislikes,'post_id'=>$post_id) ; // creates objct

   echo json_encode($myarray);// makes json file and sends as response to ajax request



}

?>