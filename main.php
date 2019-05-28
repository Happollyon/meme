<?php
require_once 'header.php';
include_once 'likes.php';
include_once 'deletepost.php';

if(($_SESSION['user']=="") || $_SESSION['password']=="" )// checks if there is a session
{
    $loggedin= FALSE; // if not, variable is turned to false
    header('location:http://localhost/meme/login.php');// and user directed to login page
}
if(isset($_POST['text']) && $_POST['text']!="") // check if something has been posted and its not empty
{   $user = $_SESSION['user'];
    $text = sanit($_POST['text']);
    $query= "INSERT INTO posts(text,user) values('$text','$user')"; //inserts into table
    queryMysql($query);
}

?>
/*Post form*/
<link rel="stylesheet" href="style/main.css" >
<div id="poster">
    <form method="post" action="">

            <input type="text" placeholder="Me faca rir" name="text">

        <div id="postar_btn"><input type="submit" value="Postar"></div>

</form>
    <br>
</div>

<?php
$result=queryMysql("SELECT post_id FROM posts WHERE user='$user'") ; // selects all posts form user
$num = $result->num_rows; // number of rows

$result2 = queryMysql(("SELECT following FROM friends WHERE user='$user'")); // selects people user is following
$num2= $result2->num_rows;// selects numbers of rows  of people user is following

$result3 = queryMysql("SELECT user FROM friends WHERE following='$user'");// select people following the user
$num3 = $result3->num_rows;// selects numbers of rows of people following user

echo "<div id='perfil-box'>".$user."  memes: ".$num."<br><br>" //shows the name of the user and the number of posts
        ."Seguindo: ".$num2."" // number of people following
        ."  Seguidores:".$num3."<br><br>" // number of followers
."</div>";


?>


<?php


 echo"<div id='post'>";
 $query="SELECT text, user, post_id, DATEDIFF(NOW(),post_date) from posts ORDER BY post_date DESC";

 $result= queryMysql($query);
 $num = $result->num_rows; //number of rows in table
 for($j=0; $j<$num;$j++) //interacts over every row
 {

     $row = $result->fetch_array(MYSQLI_ASSOC); // stores the data from posts in array
     $post_id = encrypt($row['post_id']) ;  // encrypts data
     $user_encrypted = encrypt($user);   // encrypts data
     $like_encrypted = encrypt('1');     // encrypts data
     $deslike_encrypted = encrypt('-1');     // encrypts data

                // collecting sum of likes
     $query= "SELECT SUM(likee) FROM likes WHERE post_id='".$row['post_id']."' AND likee=1";
     $result2 =queryMysql($query);
     $row2 = $result2->fetch_array(MYSQLI_ASSOC); // stores likes in array

                // collecting sum o dislikes
     $query= "SELECT SUM(likee) FROM likes WHERE post_id='".$row['post_id']."' AND likee=-1";
     $result3 =queryMysql($query);
     $row3= $result3->fetch_array(MYSQLI_ASSOC); // stores dislikes in array


     $likes = $row2['SUM(likee)']; // sum of likes
     $deslikes= $row3['SUM(likee)']; // sum of dislikes



     // outputs the posts and its likes and dislikes

     if($row['user']!=$user) // if the post wasnt made by the current user, the user isnt shown the delete option
     {
         // outputs post, user who posted it, number of likes and dislikes, number of days since it has been posted
         echo "<div id='post2'><div id='user'>postado por: ".$row['user']."</div><br>".$row['text']. "<br> <a href='main.php?like=$like_encrypted&post_id=$post_id&user=$user_encrypted'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></a> ".$likes." 	<a href='main.php?like=$deslike_encrypted&post_id=$post_id&user=$user_encrypted'> <img src='images/icons8-down-arrow-40.png'> </a> ".$deslikes.
              "<div id='data' style='color: #36FF89'>postado: "
              .$row['DATEDIFF(NOW(),post_date)']. "D atras</div></div><br>";
     } else // otherwise its shown
         {

                // everything as above + delete option.
         echo "<div id='post2'><div id='user'>postado por: " . $row['user'] . "<a href='main.php?delete=$post_id'><img src='images/icons8-trash-32.png'></a> "." </div><br>" . $row['text'] . "<br> 	<a href='main.php?like=$like_encrypted&post_id=$post_id&user=$user_encrypted'><img src='images/icons8-down-arrow-40%20-%20Copy.png'></a> " . $likes . " 	<a href='main.php?like=$deslike_encrypted&post_id=$post_id&user=$user_encrypted'> <img src='images/icons8-down-arrow-40.png'></a> " . $deslikes .
              "<div id='data' style='color: #36FF89'>postado: "
              . $row['DATEDIFF(NOW(),post_date)'] . "D atras</div></div><br>";
         }

 }
 echo "</div>"
?>

</div>
</body>
</html>
