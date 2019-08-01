<?php
require_once 'header.php';


if(isset($_POST['user'])&&isset($_POST['email'])&&isset($_POST['pass']))
    {   $username = sanit($_POST['user']);
        $email =sanit($_POST['email']);
        $password = sanit($_POST['pass']);
        $password = hash('ripemd160', $password);


        $query = "INSERT INTO member VALUES('$username', '$email', '$password')";
        queryMysql($query);

        $pathName = "user_data/".$username;
        if( !mkdir($pathName,0777,true))
        {
            die('fail creating path');
        }

        mail($email, "dont replay", "testando");

        header('location:http://localhost/meme/login.php');
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

   function passcheck()
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


    function checkUser(user)
    {
        let ajaxRequest;
        ajaxRequest = new   XMLHttpRequest();
        ajaxRequest.onreadystatechange = function ()
        { if(ajaxRequest.readyState == 4)
        { let ajaxDisplay = document.getElementById("error") ;
            ajaxDisplay.innerHTML =ajaxRequest.responseText;
            if(ajaxRequest.responseText !="")
            {
                $('input#signup-but').attr('disabled','disabled')
            }else
            {
                $('input#signup-but').removeAttr('disabled')
            }
        }

        }

        let queryString =  "?user=" + user.value;
        ajaxRequest.open("GET", "chekuser.php" + queryString, true);
        ajaxRequest.send(null);
    }

   function isEmail(email)
   {
       var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
       return regex.test(email.value);
   }

    function checkmail(email)
    {   let result = isEmail(email)
        if (!result)
        {
            $('#email').css({'background-color':'#ff358e','border':'solid','border-color':'#ffffff'})
            $('input#signup-but').attr('disabled','disabled')
        }else
        {
        $('#email').css({'background-color':'#34ff8d','border':'solid','border-color':'#ffffff'})
            $('input#signup-but').removeAttr('disabled')
        }
        let ajaxRequest;
        ajaxRequest = new   XMLHttpRequest();
        ajaxRequest.onreadystatechange = function ()
        { if(ajaxRequest.readyState == 4)
        { let ajaxDisplay = document.getElementById("error") ;
            ajaxDisplay.innerHTML =ajaxRequest.responseText;
            if(ajaxRequest.responseText !="")
            {
                $('input#signup-but').attr('disabled','disabled')
            }else
            {
                $('input#signup-but').removeAttr('disabled')
            }
        }

        }

        let queryString =  "?email=" + email.value;
        ajaxRequest.open("GET", "chekuser.php" + queryString, true);
        ajaxRequest.send(null);
    }
</script>

</body>
</html>
