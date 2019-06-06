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
echo
"<div id='post' class='$user'>"



?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</div>

<script>
  function like(like, user, post_id) {

       let urrl = 'likes.php?like='+like+'&user='+user+'&post_id='+post_id
        $.ajax({
            url: urrl,
            type: 'GET',


            success: function(data)
            {   var data = JSON.parse(data)
                let post_like  = data.post_id;
                $("#"+ post_like).text(data.likes);
                $("#D" +post_like).text(data.dislikes);
            }
        })}

  let number =0;
  {
      let user = $("#post").attr('class')
      let url = 'posts.php?number=' + number + '&user=' + user;
      $(document).ready(function () {

          $.ajax(
              {
                  url: url,
                  type: 'GET',
                  success: function (data) {
                      $("#post").append(data);
                  }

              })
      })

      number = number + 6;

      $(window).scroll(function () {
          let url = 'posts.php?number=' + number + '&user=' + user;
          if ($(window).scrollTop() + $(window).height() > $(document).height()-100) {
              $.ajax(
                  {
                      url: url,
                      type: 'GET',
                      success: function (data) {
                          $("#post").append(data);
                      }
                  })

              number = number + 6; }
      })

  }



</script>

</body>
</html>
