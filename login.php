<?php
require_once 'header.php';

if(isset($_POST['user'])&& isset($_POST['password'])) {
    $user = sanit($_POST['user']);
    $pass = sanit($_POST['password']);
    $pass = hash('ripemd160', $pass);

    $result = queryMysql("SELECT * FROM member WHERE username='$user' AND password='$pass'");
    if ($result->num_rows) {
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
