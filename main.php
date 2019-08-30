<?php
require_once 'header.php';
include_once 'likes.php';
include_once 'deletepost.php';


if(($_SESSION['user']=="") || $_SESSION['password']=="" )// checks if there is a session
{
    $loggedin= FALSE; // if not, variable is turned to false
    header('location:http://localhost/meme/login.php');// and user directed to login page
}

// ******* posting section of main page ***********

// post text only
if(isset($_POST['text']) && $_POST['text']!="" && $_FILES['image']['type'] == "")  // checks if text is set and files empty
{   $user = sanit($_SESSION['user']) ;
    $text = sanit($_POST['text']);

    $query= "INSERT INTO posts(text,user) values('$text','$user')"; //inserts text and user into table
    queryMysql($query);

}
// post file only
if(isset($_FILES['image']['type'])&& $_FILES['image']['type']!="" && $_POST['text'] == "") //checks if text is empty and files set
{

    $user = sanit($_SESSION['user']) ;
    $file_id = uniqid("$user", TRUE); // creates a unique file id -> prefixed with user(16) id(23) .jpg
    $fileName = "user_data/".$user."/".$file_id.".jpg";
    move_uploaded_file($_FILES['image']['tmp_name'],  $fileName); // saves the file in user_data/userfolder/filed.jpg
    queryMysql("INSERT INTO posts(user,file_id) values('$user','$file_id')");
}
//file and text
if(isset($_POST['text']) && $_POST['text']!="" && isset($_FILES['image']['type']) && $_FILES['image']['type'] != "")  // checks if both are set
{   $user = sanit($_SESSION['user']);
    $text = sanit($_POST['text']);
    $file_id = uniqid("$user", TRUE);// creates a unique file id -> prefixed with user(16) id(23) .jpg
    $fileName = "user_data/".$user."/".$file_id.".jpg";
    move_uploaded_file($_FILES['image']['tmp_name'],  $fileName); // saves the file in user_data/userfolder/filed.jpg

    $query= "INSERT INTO posts(text,user,file_id) values('$text','$user','$file_id')"; //inserts text, user and file into table
    queryMysql($query);

}

?>

    <!-- HTML here -->

<link rel="stylesheet" href="style/main.css" >

<div id="poster">
    <form method="post"autocomplete='off' action="" enctype="multipart/form-data">

            <input autocomplete='off' id='texto' onkeyup="counter()" type="text" placeholder="Me faca rir" name="text">

            <input autocomplete='off' type="file" size="14" name="image">
            <div id="numb"></div>
        <div id="postar_btn"><input type="submit" value="Postar"></div>

</form>
    <br>
</div>

<?php
if(file_exists("user_data/$user/$user.jpg")) // checks if user has profile pic
{
    $path_profile = "user_data/$user/$user.jpg ";  // saves path to pic in var
} else {
    $path_profile = "images/avatars-icon-16.jpg"; // or uses a avatar
}

$result=queryMysql("SELECT post_id FROM posts WHERE user='$user'") ; // selects all posts form user
$num = $result->num_rows; // number of rows

$result2 = queryMysql(("SELECT following FROM friends WHERE user='$user'")); // selects people user is following
$num2= $result2->num_rows;// selects numbers of rows  of people user is following

$result3 = queryMysql("SELECT user FROM friends WHERE following='$user'");// select people following the user
$num3 = $result3->num_rows;// selects numbers of rows of people following user

echo "<div id='perfil-box'><div id='perfil-userAndPic'><a href='profile.php?profile=$user&user=$user'><img src='$path_profile' id='avatar'> </a>".$user."  </div><div id='perfil-data'><div id='meme'><div>memes</div>".$num."</div>" //shows the name of the user and the number of posts
        ."<div id='seguindo'><div>Seguindo</div> ".$num2."</div>" // number of people following
        ." <div id='seguidores'><div>Seguidores</div>".$num3."</div>" // number of followers
."</div></div>";
echo
"<div id='post' class='$user'>"



?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</div>

<script>
    // like and dislike ajax using jquery
  function like(like, user, post_id)        //function on click with user, post id and like value parammters
  {

       let urrl = 'likes.php?like='+like+'&user='+user+'&post_id='+post_id   // creates the url GET
       $.ajax({    // jquery ajax fuction
            url: urrl,
            type: 'GET',


            success: function(data) // on success it receives a json file
            {   var data = JSON.parse(data)  // parses it
                let post_like  = data.post_id; // each post has a div id = the post id

                $('#'+post_like).text(data.likes); // access and updates numb o likes by div =id
                $("#D"+post_like).text(data.dislikes); // access and updates numb o dislikes by div =id
            }
        })
  }


    function like_comment(like, user, comment_id)        //function on click with user, post id and like value parammters
    {

        let urrl = 'like_comment.php?like='+like+'&user='+user+'&post_id='+comment_id   // creates the url GET
        $.ajax({    // jquery ajax fuction
            url: urrl,
            type: 'GET',


            success: function(data) // on success it receives a json file
            {   var data = JSON.parse(data)  // parses it
                let comment_like  = data.post_id; // each comment has a div class = the post id
                $("#L"+comment_like).text(data.likes); // access and updates numb o likes by div =class
                $("#D"+comment_like).text(data.dislikes); // access and updates numb o dislikes by div =class
            }
        })
    }


        // Ajax loading posts
  let number =0; //number of posts to be ignored so isn't loaded again
  {
      let user = $("#post").attr('class') //user value taken from class
      let url = 'posts.php?number=' + number + '&user=' + user;  // creates url
      $(document).ready(function ()
      { // ajax is called when page is ready

          $.ajax(
              {
                  url: url,
                  type: 'GET',  //get
                  success: function (data) // on success of the call
                  {
                      $("#post").append(data); // appends 6 posts
                  }

              })
      })

      number = number + 6; //number of posts to be ignored so isn't loaded again is increased


           // ajax when page is scrolled to almost end of page
           $(window).scroll(function ()
      {
          let url = 'posts.php?number=' + number + '&user=' + user;  // url to be passed is created
          if ($(window).scrollTop() + $(window).height() > $(document).height()-100)
          {
              $.ajax(
                  {
                      url: url,
                      type: 'GET',
                      success: function (data) {
                          $("#post").append(data); // response is appended to page
                      }
                  })

              number = number + 6; //number of posts to be ignored so isn't loaded again is increased
          }
      })

  }

 function counter() // this funtion counts the number of chars the post has
 {
    let num_char = $('#texto').val()
     if(num_char.length<600)
     {
         $('#numb').text(num_char.length)
     }else
     {
         $('#numb').text('limite: 600')
     }

 }







 // when post clicked this function makes an ajax call to database to retrieve comments
    let  click = 0;


 function open_coments(post_id,user) // passes the post id and the user viewing the post
 {

     if (click==1 ) // if icon has being clicked once
     {
         $('div').remove('.comments'); //closes
         $('div').remove('.commenter');//closes

         click=0; // set to zero
         return ;
     }


     let form = // this variable holds the form used to make new comments

         "   <div class='commenter'> <input id='comment"+post_id+"' autocomplete=\"off\" type=\"text\" name=\"comment\" placeholder=\"comente esse meme\" maxlength=\"100\">\n" +
         "    <input type='submit' onclick='make_comment("+post_id+","+ "\""+user+"\""+")'value='commentar'><div><br>\n";

     $('.'+post_id).append(form); // when the comment section opens the form is inserted
     let number = 0; // number of posts loaded
     let url = 'comments.php?post_id=' +post_id+'&user='+user +'&number='+number+"&limit=6";  // url for ajax call
     $.ajax(
         {
             url:url,
             type:'GET',  // type of request
             success: function (data)// on success
            {
                $('.'+post_id).append(data); //response (comments) is appended to the post
            }
        }
     )

     let comments_left=0;  //variable used to stop request
    number = number + 6; // number of posts loaded
     $(this).on('scroll', function () // every time page is scrolled function is called
     {
         let url = 'comments.php?post_id=' +post_id+'&user='+user +'&number='+number+"&limit=6"; // url to be passed is created
         if ($('.'+post_id).scrollTop() + $('.'+ post_id).innerHeight()>= $('.'+ post_id)[0].scrollHeight) // if pixels scrolled + post hight > hte
         {
             if(comments_left == 1) // if there is no more comments to be loaded
             {
                 return;
             }else
             {


             $.ajax(
                 {
                     url: url,
                     type: 'GET',
                     success: function (data) {
                         $('.'+post_id).append(data); // response is appended to post
                       if (data=='')
                       {
                           comments_left = 1;
                       }
                     }

                 }) }

             number = number + 6; //number of posts to be ignored so isn't loaded again is increased
         }

     })
 click =1; 
 }


    function delete_post(post_id) // function used to delete posts using ajax
    {
        let url = "deletepost.php?delete=" +post_id; //url

        // ajax call
        $.ajax( {
            url: url,
            type: 'GET',
            success: function()
            {
                $('.' + post_id).css({'display':'none'}); // on success posts disapears
            }
        })

    }
function delete_comment(comment_id) // function used to delete comments using ajax
{
    let url = "delete_comment.php?delete_comment=" +comment_id; //url

    $.ajax(  // ajax call
        {
        url: url,
        type: 'GET',
        success: function()
        {
            $('#' + comment_id).css({'display':'none'}); // on success comment disapears
        }
    })

}
function make_comment(post_id,user) // function used to make camment using ajax
{  let comment = $('#comment'+post_id).val(); // takes value of input "the comment"
    $('#comment' + post_id).val(''); // sets input back to blank
    let url = "comments.php?post_id=" +post_id+ "&user=" +user+ "&comment=" +comment+"&limit=1&number=0"; // creates a url

    $.ajax( // makes ajax call
        {
            url: url,
            type: 'GET',
            success: function(data)
            {

               if($('div').is('.comments')) // if post already has comments
               {
                   $(data).insertBefore('.comments:first'); // inserts it before 1st comment
               }else
               {
                   $('.' + post_id).append(data); // if not appends it post
               }
            }
        })


}


</script>

</body>
</html>
