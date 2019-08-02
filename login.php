<?php
require_once 'header.php';

if(isset($_POST['user'])&& isset($_POST['password'])) // checks if user and password are set
{
    $user = sanit($_POST['user']);  // sanitizes it
    $pass = sanit($_POST['password']); // sanitizes it
    $pass = hash('ripemd160', $pass); // sanitizes it

    $result = queryMysql("SELECT * FROM member WHERE username='$user' AND password='$pass'"); //checks values against database
    if ($result->num_rows)  // if found
    {
        // creates section
        $_SESSION['user'] = $user;
        $_SESSION['password'] = $pass;

        $loggedin = TRUE;
        header('location:http://localhost/meme/main.php');
    } else {
        echo "<p>verifique usuario/senha.</p>";
    }
}


?>
<link rel="stylesheet" href="style/login.css">
<div id="loginform">

<form action="login.php"autocomplete='off'  method="post">
    <div id="signin_flex">
        <div>Usuario</div>
    <input type="text" autocomplete='off' placeholder="Entre seu usuario" name="user">
        <div>Senha</div>
    <input type="password" autocomplete='off'  placeholder="Entre sua senha" name="password">
   <div id="signup-but"><input type="submit" value="Login"></div>
    </div>
</form>
</div>
</div>
</body>
</html>
