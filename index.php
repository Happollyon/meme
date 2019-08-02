<?php
require_once 'header.php';

?>
<link href="style/index.css" rel="stylesheet">
<div id="error"></div>
<div id="signup">


    <form method="post" autocomplete='off' action="register.php" onsubmit="stop()">
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
<div id="texto">
    Junte-se e ganhe <br>reconhecimento pelos <br>seus memes.
</div>
<div id="imagem">
    <img src="images/cc4.jpeg">

</div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script>

    function passcheck() // function to check that password matches
    {let pass =  $('#pass').val();  // gets value of pass
        let pasCheck= $('#passCheck').val(); // gess value of passcheck value
        if(pass!=pasCheck)  // both values are different
        {
            $('#passCheck').css({'background-color':'#ff358e','border':'solid','border-color':'#ffffff'})  // changes style
            $('input#signup-but').attr('disabled','disabled') // disables submit button
        }
        else
        {
            $('#passCheck').css({'background-color':'#6cff7a','border':'solid','border-color':'#ffffff'})
            $('input#signup-but').removeAttr('disabled') // enables submit button

        }


    }

    // this function makes an ajax call to the database to check if there is a user using user name
    function checkUser(user)
    {
        let ajaxRequest;
        ajaxRequest = new   XMLHttpRequest();
        ajaxRequest.onreadystatechange = function ()
        { if(ajaxRequest.readyState == 4)
        { let ajaxDisplay = document.getElementById("error") ; // creates an object
            ajaxDisplay.innerHTML =ajaxRequest.responseText;  // response is placed in object
            if(ajaxRequest.responseText !="") // if there a user
            {
                $('input#signup-but').attr('disabled','disabled') // disables button
            }else
            {
                $('input#signup-but').removeAttr('disabled')// enables button
            }
        }

        }

        let queryString =  "?user=" + user.value;
        ajaxRequest.open("GET", "chekuser.php" + queryString, true);
        ajaxRequest.send(null);
    }

    function isEmail(email) // this function checks if value is an email
    {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email.value);
    }


    // this function makes an ajax call to check if email is available
    function checkmail(email)
    {   let result = isEmail(email)
        if (!result) // if false
        {
            $('#email').css({'background-color':'#ff358e','border':'solid','border-color':'#ffffff'}) // style
            $('input#signup-but').attr('disabled','disabled') // button disabled
        }else
        {
            $('#email').css({'background-color':'#34ff8d','border':'solid','border-color':'#ffffff'}) // style
            $('input#signup-but').removeAttr('disabled') // button enabled
        }
        let ajaxRequest;
        ajaxRequest = new   XMLHttpRequest();
        ajaxRequest.onreadystatechange = function ()
        { if(ajaxRequest.readyState == 4)
        { let ajaxDisplay = document.getElementById("error") ;
            ajaxDisplay.innerHTML =ajaxRequest.responseText;
            if(ajaxRequest.responseText !="")
            {
                $('input#signup-but').attr('disabled','disabled') // button disabled
            }else
            {
                $('input#signup-but').removeAttr('disabled') // button enabled
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
