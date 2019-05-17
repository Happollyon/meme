<?php
require_once 'header.php';
if(isset($_POST['user'])&& isset($_POST['password']))
{   $user = sanit($_POST['user']);
    $pass = sanit($_POST['password']);
    $result = queryMysql("SELECT * FROM member WHERE username='$user' AND password='$pass'");
    if($result->num_rows)
    {   $_SESSION['user'] = $user;
        $_SESSION['password'] =$pass;
        echo"<p>you are now logged in</p>";
        $loggedin =TRUE;
    }else
    {
        echo"<p>verifique usuario/senha.</p>";
    }
}else{
    echo "<p>Entre usuario e senha</p>";
}
?>


<form action="login.php" method="post">
    <input type="text" placeholder="Entre seu usuario" name="user">
    <input type="password" placeholder="Entre sua senha" name="password">
    <input type="submit" value="Login">
</form>
</body>
</html>
