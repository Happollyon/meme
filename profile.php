<?php
require_once 'header.php';
include_once 'likes.php';
include_once 'deletepost.php';
$profile_user = $_GET['profile'];
$user = $_GET['user'];
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


if(isset($_FILES['profile_picture'])) {
    $user = sanit($_SESSION['user']);

    $fileName = "user_data/" . $user . "/" . $user . ".jpg";
    move_uploaded_file($_FILES['profile_picture']['tmp_name'], $fileName); // saves the file in user_data/userfolder/filed.jpg
}
?>
<?php if($profile_user == $_SESSION['user']){ ?>
<link rel="stylesheet" href="style/main.css" >
<div id="poster">
    <form autocomplete='off' method="post" action="" enctype="multipart/form-data">

        <input autocomplete='off' type="text" placeholder="Me faca rir" name="text">
        <input type="file" size="14" name="image">

        <div id="postar_btn"><input type="submit" value="Postar"></div>

    </form>
    <br>
</div>
<?php
} else{echo"<link rel=\"stylesheet\" href=\"style/profille.css\" >";}
?>
<?php
if(file_exists("user_data/$profile_user/$profile_user.jpg")) // checks if user has profile pic
{
    $path_profile = "user_data/$profile_user/$profile_user.jpg ";  // saves path to pic in var
} else {
    $path_profile = "images/avatars-icon-16.jpg"; // or uses a avatar
}

$result=queryMysql("SELECT post_id FROM posts WHERE user='$profile_user'") ; // selects all posts form user
$num = $result->num_rows; // number of rows

$result2 = queryMysql(("SELECT following FROM friends WHERE user='$$profile_user'")); // selects people user is following
$num2= $result2->num_rows;// selects numbers of rows  of people user is following

$result3 = queryMysql("SELECT user FROM friends WHERE following='$profile_user'");// select people following the user
$num3 = $result3->num_rows;// selects numbers of rows of people following user

$result4 = queryMysql("SELECT * FROM FRIENDS WHERE user='$user' AND following='$profile_user'");
$num4=$result4->num_rows;
if(!$num4)
{
    $button= "<button id='follow' onclick='follow(\"$user\",\"$profile_user\")'>follow</button>";
} else
{
    $button="<button id='unfollow' onclick='unfollow(\"$user\",\"$profile_user\")'>unfollow</button>";
}

if($profile_user !=$user)
{
echo "<div id='perfil-box' class='$profile_user'><div><img src='$path_profile' id='avatar'>".$profile_user."</div> <div id='follow_button'>$button</div><div id='inf'><div>memes: <div>".$num."</div>" //shows the name of the user and the number of posts
    ."</div><div>Seguindo: <div id='following'>".$num2 // number of people following
    ." </div></div> <div>Seguidores:<div id='followers'>".$num3."</div></div></div>" // number of followers
    ."</div>";
}else
{
    echo "<div id='perfil-box' class='$profile_user'><img src='$path_profile' id='avatar'>".$profile_user."  memes: ".$num."<br><br>" //shows the name of the user and the number of posts
        ."   <form autocomplete='off' method='post' action='' enctype='multipart/form-data'> <input autocomplete='off' type='file' size='14' name='profile_picture'> "
        ."<input id='but'type='submit' value='update'></form>"
        ."Seguindo: ".$num2."" // number of people following
        ."  Seguidores:".$num3."<br><br>" // number of followers
        ."</div>";
}

echo
"<div id='post' class='$user'>"



?>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</div>

<script>

    // follow function
    function follow(user, profile_user)
    {
        let urrl = 'follow.php?user='+user+'&profile='+profile_user;
        $.ajax({
            url: urrl,
            type:'GET',
            success:function (data)
            { var data = JSON.parse(data);
            $('#following').text(data.following);
            $('#followers').text(data.followers);

            }
        })
        var but = '<button id="unfollow" onclick=unfollow("'+user+'","'+profile_user+'")>unfollow</button>'
        $('#follow_button').html(but)
    }

    function unfollow(user, profile_user)
    {
        let urrl = 'follow.php?user='+user+'&unfollow_profile='+profile_user;
        $.ajax({
            url: urrl,
            type:'GET',
            success:function (data)
            { var data = JSON.parse(data);
                $('#following').text(data.following);
                $('#followers').text(data.followers);

            }
        })
        var but='<button id="follow" onclick=follow("'+user+'","'+profile_user+'")>follow</button>'
        $('#follow_button').html(but)
    }





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
                $("#"+ post_like).text(data.likes); // access and updates numb o likes by div =id
                $("#D" +post_like).text(data.dislikes); // access and updates numb o dislikes by div =id
            }
        })
    }


    // Ajax loading posts
    let number =0; //number of posts to be ignored so isn't loaded again
    {   let profile = $("#perfil-box").attr('class');
        let user = $("#post").attr('class') //user value taken from class
        let url = 'posts.php?number=' + number + '&profile=' + profile+ '&session_user=' +user;  // creates url
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
            let url = 'posts.php?number=' + number + '&profile=' + profile+'&session_user=' +user;  // url to be passed is created
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



</script>

</body>
</html>
