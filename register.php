<?php
require_once 'header.php';


if(isset($_POST['user'])&&isset($_POST['email'])&&isset($_POST['pass']))
    {   $username = sanit($_POST['user']);
        $email =sanit($_POST['email']);
        $password = sanit($_POST['pass']);
        $password = hash('ripemd160', $password);


        $query = "INSERT INTO member VALUES('$username', '$email', '$password')";
        queryMysql($query);
        header('location:http://localhost/meme/login.php');

        exit();


    }


?>
<link href="style/register.css" rel="stylesheet">
<div id="signup">


    <form method="post" action="register.php">
        <div id="signup_flex">
            <div>Usuario</div>

            <input type="text" name="user"placeholder="Entre seu usuario">

            <div>Email</div>

            <input type="text" name="email" placeholder="Entre seu email">

            <div>Senha</div>

            <input type="password" name="pass"placeholder="Entre sua senha">

            <div>confirme sua senha</div>

            <input type="password" name="pass" placeholder="Confirme sua senha">

            <div id="btn">
                <input type="submit" value="Sign up" id="signup-but">
            </div>
        </div>
    </form>

</div>

</div>

</body>
</html>
