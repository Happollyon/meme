<?php
include_once 'functions.php';

$limit  = 6     ;
$previous_limit = $_GET['number'];

$user = $_GET['user'];


$query = "SELECT text, user, post_id, DATEDIFF(NOW(),post_date) from posts ORDER BY post_date DESC LIMIT $limit OFFSET $previous_limit";


$result = queryMysql( $query);

$num = $result->num_rows; //number of rows in table

for ($j = 0; $j < $num; $j++) //interacts over every row
{

    $row = $result->fetch_array(MYSQLI_ASSOC); // stores the data from posts in array
    $post_id = $row['post_id'];
    $post_id_encrypted = encrypt($row['post_id']);  // encrypts data

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


    // outputs the posts and its likes and dislikes

    if ($row['user'] != $user) // if the post wasnt made by the current user, the user isnt shown the delete option
    {
        // outputs post, user who posted it, number of likes and dislikes, number of days since it has been posted
        echo "<div id='post2'><div id='user'>" . $row['user'] . "</div> <div id='content'> " . $row['text'] .
            "</div><div id='interaction'> <div id='likes'> <button id='like'  onclick='like(\"$like_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></button> <div id='$post_id'>" . $likes .
            "</div>	<button onclick='like(\"$deslike_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'> <img src='images/icons8-down-arrow-40.png'> </button>  <div id='D$post_id'>" . $deslikes .
            "</div></div><button><img src='images/icons8-speech-bubble-40.png'></button></div><div id='data' style='color: #36FF89'>postado: "
            . $row['DATEDIFF(NOW(),post_date)'] . "D atras</div></div> <br>";
    } else // otherwise its shown
    {

        // everything as above + delete option.
        echo "<div id='post2'><div id='user'> " . $row['user'] .
            "<a href='main.php?delete=$post_id'><img src='images/icons8-trash-32.png'></a> " .
            " </div><div id='content'> " . $row['text'] . "</div> <div id='interaction'><div id='likes'><button onclick='like(\"$like_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></button><div id='$post_id'>" . $likes .
            " </div>	<button onclick='like(\"$deslike_encrypted\",\"$user_encrypted\",\"$post_id_encrypted\")'> <img src='images/icons8-down-arrow-40.png'></button>  <div id='D$post_id'> " . $deslikes .
            "</div></div><button><img src='images/icons8-speech-bubble-40.png'></button></div><div id='data' style='color: #36FF89'>postado: "
            . $row['DATEDIFF(NOW(),post_date)'] . "D atras</div></div><br>";
    }

}
echo "</div>"

?>