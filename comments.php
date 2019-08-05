<?php
require_once 'functions.php';
$limit  = sanit($_GET['limit']); // number of comments to load at a time
$previous_limit = sanit($_GET['number']); // number of comments to offset
// receive a request passing user, post_id, number of post offset

if(isset($_GET['user'])&&isset($_GET['post_id'])&&isset($_GET['comment']))
{
    $user= sanit($_GET['user']);
    $post_id = sanit($_GET['post_id']);
    $comment_into_dataBase = sanit($_GET['comment']);
    queryMysql("INSERT INTO comments(comment_text,post_id,user_comment) VALUES('$comment_into_dataBase','$post_id','$user')");

}
if(isset($_GET['user']) && isset($_GET['post_id']))
{
    $user = sanit($_GET['user']); // gets from the request the user
    $post_id= sanit($_GET['post_id']);// gets from the request the post id
    $query = "SELECT comment_text, user_comment, comment_id, post_id, DATEDIFF(NOW(),comment_time) from comments WHERE post_id='$post_id'  ORDER BY comment_timE DESC LIMIT $limit OFFSET $previous_limit";
    $result = queryMysql($query); // gets data from comments
    $num = $result->num_rows;

    // selecting user who made the post
    $post_owner = queryMysql("SELECT user FROM posts WHERE post_id = '$post_id'");  // selecting user from table post.
    $post_owner = $post_owner->fetch_array(MYSQLI_ASSOC);
    $post_owner = $post_owner['user'];


    for($i=0; $i<$num; $i++)
    {   $user_encrypted = encrypt($user);       // encrypts the user
        $row =  $result->fetch_array(MYSQLI_ASSOC);
        $comment = sanit($row['comment_text']);         // holds the text
        $user_comment= sanit($row['user_comment']);         // user who commented
        $comment_id = $row['comment_id'];            // id of comment
        $comment_id_encr=encrypt($row['comment_id']);       // encrypts the comment
        $time = sanit($row['DATEDIFF(NOW(),comment_time)']);        // time de comment was made

        // collecting sum of likes
        $query = "SELECT SUM(likee) FROM comments_likes WHERE comment_id='" . $comment_id . "' AND likee=1";
        $result2 = queryMysql($query);
        $row2 = $result2->fetch_array(MYSQLI_ASSOC); // stores likes in array

        // collecting sum o dislikes
        $query = "SELECT SUM(likee) FROM comments_likes WHERE comment_id='" . $comment_id . "' AND likee=-1";
        $result3 = queryMysql($query);
        $row3 = $result3->fetch_array(MYSQLI_ASSOC);        // stores dislikes in array

        $like_encrypted =encrypt(1);  // encrypts  like

        $deslike_encrypted=encrypt(-1);   // encrypts dislike
        $likes = $row2['SUM(likee)'];  // stores like in a variable
        $deslikes= $row3['SUM(likee)']; // stores dislike in a variable
        if(file_exists("user_data/$user_comment/$user_comment.jpg"))        // checks if user has profile picture
        {
            $path_profile = "user_data/$user_comment/$user_comment.jpg ";      // saves path to pic in variable
        }
        else
        {
            $path_profile = "images/avatars-icon-16.jpg"; // if not uses avatar
        }

        // return comments with likes dislikes [done]

        // fi user is who commented he can delete it [done]
        // who make teh post can delete comments [done]
        // comments have to be called using limit [partially done]



        if($user == $user_comment) // who makes the comment can delete his comment
        {
            echo "<div class='comments' id='$comment_id'><div id='user'><img onclick='delete_comment(\"$comment_id\")' src='images/icons8-delete-24.png'><img src='$path_profile' id='avatar'>$user_comment</div><div id='text'>$comment</div> $time dias atras <div id='interaction'> <div id='likes'> 
            <button id='like'  onclick='like_comment(\"$like_encrypted\",\"$user_encrypted\",\"$comment_id_encr\")'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></button> <div id='$comment_id'> $likes 
            </div>	<button onclick='like_comment(\"$deslike_encrypted\",\"$user_encrypted\",\"$comment_id_encr\")'> <img src='images/icons8-down-arrow-40.png'> </button>  <div id='D$comment_id'>  $deslikes 
            </div></div></button></div></div>";
        }elseif($user == $post_owner) // who made the post can delete any comment
        {
            echo "<div id='comments' class='$comment_id'><div id='user'><img src='images/icons8-delete-24.png'><img src='$path_profile' id='avatar'>$user_comment</div>delete<div id='text'>$comment</div> $time dias atras <div id='interaction'> <div id='likes'> 
            <button id='like'  onclick='like_comment(\"$like_encrypted\",\"$user_encrypted\",\"$comment_id_encr\")'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></button> <div id='$comment_id'> $likes 
            </div>	<button onclick='like_comment(\"$deslike_encrypted\",\"$user_encrypted\",\"$comment_id_encr\")'> <img src='images/icons8-down-arrow-40.png'> </button>  <div id='D$comment_id'>  $deslikes 
            </div></div></button></div></div>";
        }else// cant delete comments
        {
            echo "<div id='comments' class='$comment_id'><div id='user'><img src='$path_profile' id='avatar'>$user_comment</div><div id='text'>$comment</div> $time dias atras <div id='interaction'> <div id='likes'> 
            <button id='like'  onclick='like_comment(\"$like_encrypted\",\"$user_encrypted\",\"$comment_id_encr\")'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></button> <div id='$comment_id'> $likes 
            </div>	<button onclick='like_comment(\"$deslike_encrypted\",\"$user_encrypted\",\"$comment_id_encr\")'> <img src='images/icons8-down-arrow-40.png'> </button>  <div id='D$comment_id'>  $deslikes 
            </div></div></button></div></div>";
        }


    }
}
?>