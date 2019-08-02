<?php
require_once 'header.php';


if(isset($_POST['user'])&&isset($_POST['email'])&&isset($_POST['pass'])) // checks if values are set
    {   $username = sanit($_POST['user']); // sanitizes input
        $email =sanit($_POST['email']);// sanitizes input
        $password = sanit($_POST['pass']);// sanitizes input
        $password = hash('ripemd160', $password); // hashing


        $query = "INSERT INTO member VALUES('$username', '$email', '$password')"; // query to insert data into data base
        queryMysql($query);

        $pathName = "user_data/".$username; // creates a path for userdata i.e profile picture
        if( !mkdir($pathName,0777,true)) // if there is an error creating plat
        {
            die('fail creating path');
        }

        mail($email, "dont replay", "testando");

        header('location:http://localhost/meme/login.php'); // redirects user to login page
        exit();


    }


?>

<link href="style/register.css" rel="stylesheet">
<div id="error"></div>
<div id="signup">


    <form autocomplete='off' method="post" action="register.php">
        <div id="signup_flex">
            <div>Usuario</div>

            <input autocomplete='off' type="text" name="user"placeholder="Entre seu usuario" onblur="checkUser(this)" required maxlength="16" autofocus >

            <div>Email</div>

            <input autocomplete='off' type="text" name="email" id="email" placeholder="Entre seu email"onkeyup="checkmail(this)" required>

            <div>Senha</div>

            <input id="pass" autocomplete='off' type="password" name="pass"placeholder="Entre sua senha" required>

            <div>confirme sua senha</div>

            <input id="passCheck" autocomplete='off' type="password" name="pass" onkeyup="passcheck()" placeholder="Confirme sua senha" required>

            <div id="btn">
                <input type="submit" value="Sign up" id="signup-but">
            </div>
        </div>
    </form>

</div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>

   function passcheck() // function to che
   {let pass =  $('#pass').val();
       let pasCheck= $('#passCheck').val();
       if(pass!=pasCheck)
       {
           $('#passCheck').css({'background-color':'#ff358e','border':'solid','border-color':'#ffffff'})
           $('input#signup-but').attr('disabled','disabled')
       }
       else
       {
           $('#passCheck').css({'background-color':'#6cff7a','border':'solid','border-color':'#ffffff'})
           $('input#signup-but').removeAttr('disabled')

       }


   }


    function checkUser(user) // fucntion makes an ajax call to database to check if there is a user
    {
        let ajaxRequest;
        ajaxRequest = new   XMLHttpRequest();
        ajaxRequest.onreadystatechange = function ()
        { if(ajaxRequest.readyState == 4)
        { let ajaxDisplay = document.getElementById("error") ;
            ajaxDisplay.innerHTML =ajaxRequest.responseText; // response is appended in error div
            if(ajaxRequest.responseText !="") // if there is one
            {
                $('input#signup-but').attr('disabled','disabled')  // sing up button is disable
            }else
            {
                $('input#signup-but').removeAttr('disabled') // if not is enabled
            }
        }

        }

        let queryString =  "?user=" + user.value;
        ajaxRequest.open("GET", "chekuser.php" + queryString, true); // call is made
        ajaxRequest.send(null);
    }

   function isEmail(email) // fucntion to check if value is an email
   {
       var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
       return regex.test(email.value);
   }

    function checkmail(email)
    {   let result = isEmail(email)
        if (!result) // if isnt email
        {
            $('#email').css({'background-color':'#ff358e','border':'solid','border-color':'#ffffff'}) // style changes
            $('input#signup-but').attr('disabled','disabled') // sign up button is disabled
        }else
        {
        $('#email').css({'background-color':'#34ff8d','border':'solid','border-color':'#ffffff'}) //style changes
            $('input#signup-but').removeAttr('disabled') // sing up button is enabled
        }
        let ajaxRequest;
        ajaxRequest = new   XMLHttpRequest();
        ajaxRequest.onreadystatechange = function ()
        { if(ajaxRequest.readyState == 4)
        { let ajaxDisplay = document.getElementById("error") ;
            ajaxDisplay.innerHTML =ajaxRequest.responseText;
            if(ajaxRequest.responseText !="")
            {
                $('input#signup-but').attr('disabled','disabled') // if there is an email already registered
            }else
            {
                $('input#signup-but').removeAttr('disabled')
            }
        }

        }

        let queryString =  "?email=" + email.value;
        ajaxRequest.open("GET", "chekuser.php" + queryString, true); // ajax call is mad
        ajaxRequest.send(null);
    }
</script>

</body>
</html>
