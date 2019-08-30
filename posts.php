<?php
include_once 'functions.php';

$limit  = 6; // number of posts to load at a time
$previous_limit = $_GET['number']; // number of posts to offset

$user ="";
$hour ="";

$user_profile = "";
if(isset($_GET['hour']))

{$hour = sanit($_GET['hour']);
    $query= "SELECT p.*,DATEDIFF(NOW(),post_date), l.total_likes
FROM Posts p 
JOIN ( SELECT post_id, SUM( CASE WHEN likee = 1 THEN 1 ELSE 0 END ) as total_likes
       FROM likes
       WHERE like_time>= DATE_SUB(NOW(),INTERVAL $hour HOUR)
       GROUP BY post_id
    ) L
ON p.post_id = l.post_id
ORDER BY l.total_likes DESC LIMIT $limit OFFSET $previous_limit";
    $result =queryMysql($query);
    $num = $result->num_rows;

}
if(isset($_GET['user']) && $user_profile =="" && $hour =="")
{
    $user = sanit($_GET['user']);
    $query = "SELECT text, user, post_id, file_id, DATEDIFF(NOW(),post_date) from posts ORDER BY post_date DESC LIMIT $limit OFFSET $previous_limit";


    $result = queryMysql( $query);

    $num = $result->num_rows; //number of rows in table
}elseif (isset($_GET['profile']) && $hour =="")
{   $profile_user =$_GET['profile'];
    $query = "SELECT text, user, post_id, file_id, DATEDIFF(NOW(),post_date) from posts WHERE user='$profile_user'  ORDER BY post_date DESC LIMIT $limit OFFSET $previous_limit";
    $result = queryMysql( $query);
    $num = $result->num_rows; //number of rows in table
    $user=$_GET['session_user'];
}



for ($j = 0; $j < $num; $j++) //interacts over every row
{

    $row = $result->fetch_array(MYSQLI_ASSOC); // stores the data from posts in array
    $post_id = $row['post_id'];
    $post_id_encrypted = encrypt($row['post_id']);  // encrypts data
    $file_id= $row['file_id'];

    $user_encrypted = encrypt($user);    // encrypts data
    $like_encrypted = encrypt('1');     // encrypts data
    $deslike_encrypted = encrypt('-1');     // encrypts data

    // collecting sum of likes
    $query = "SELECT SUM(likee) FROM likes WHERE post_id='" . $row['post_id'] . "' AND likee=1";
    $result2 = queryMysql($query);
    $row2 = $result2->fetch_array(MYSQLI_ASSOC); // stores likes in array

    // collecting sum o dislikes
    $query = "SELECT SUM(likee) FROM likes WHERE post_id='" . $row['post_id'] . "' AND likee=-1";
    $result3 = queryMysql($query);
    $row3 = $result3->fetch_array(MYSQLI_ASSOC); // stores dislikes in array


    $likes = $row2['SUM(likee)']; // sum of likes
    $deslikes = $row3['SUM(likee)']; // sum of dislikes

    $post_user = $row['user'];

    if(file_exists("user_data/$post_user/$post_user.jpg")) // checks if user has profile picture
    {
        $path_profile = "user_data/$post_user/$post_user.jpg " ; // saves path to pic in variable
    }
    else
    {
        $path_profile = "images/avatars-icon-16.jpg"; // if not uses avatar
    }

     if($file_id) // checks has file
     {
         $file_path="user_data/$post_user/$file_id.jpg"; // if so saves it to variable
     }else{$file_path ="";} // if not is emty





    // outputs the posts and its likes and dislikes

    if ($row['user'] != $user) // if the post wasnt made by the current user, the user isnt shown the delete option
    {
        // outputs post, user who posted it, number of likes and dislikes, number of days since it has been posted
        echo "<div id='post2'  class='$post_id' ><div id='user'> <div id='userAndPic'><a href='profile.php?profile=$post_user&user=$user'><img src='$path_profile' id='avatar'></a>" . $row['user'] . "</div></div> <div id='content'> " . $row['text'] .
            "<img src='$file_path'></div><div id='interaction'> <div id='likes'> <button id='like'  onclick='like(\"$like_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></button> <div id='$post_id'>" . $likes .
            "</div>	<button onclick='like(\"$deslike_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'> <img src='images/icons8-down-arrow-40.png'> </button>  <div id='D$post_id'>" . $deslikes .
            "</div></div><button><img onclick=open_coments(\"$post_id\",\"$user_encrypted\") src='images/icons8-speech-bubble-40.png'></button></div><div id='data' style='color: #0F7173'>postado: "
            . $row['DATEDIFF(NOW(),post_date)'] . "D atras</div></div> ";
    } else // otherwise its shown
    {

        // everything as above + delete option.
        echo "<div id='post2' class='$post_id'><div id='user'><div id='userAndPic'> <a href='profile.php?profile=$post_user&user=$user'><img src='$path_profile' id='avatar'> </a>" . $row['user'] .
            "</div> <a onclick='delete_post(\"$post_id\")'><img src='images/icons8-trash-32.png'></a> " .
            "</div><div id='content'> " . $row['text'] ."<img src='$file_path'></div> <div id='interaction'><div id='likes'><button onclick='like(\"$like_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></button><div id='$post_id'>" . $likes .
            " </div>	<button onclick='like(\"$deslike_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'> <img src='images/icons8-down-arrow-40.png'></button>  <div id='D$post_id'> " . $deslikes .
            "</div></div><button><img  onclick=open_coments(\"$post_id\",\"$user_encrypted\") src='images/icons8-speech-bubble-40.png'></button></div><div id='data' >postado: "
            . $row['DATEDIFF(NOW(),post_date)'] . "D atras</div></div>";
    }

}
echo "</div>"

?>