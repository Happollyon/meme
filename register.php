<?php
include_once'header.php';


if(isset($_POST['user'])&&isset($_POST['email'])&&isset($_POST['pass']))
    {   $username = sanit($_POST['user']);
        $email =sanit($_POST['email']);
        $password = sanit($_POST['pass']);
        $password = hash('ripemd160', $password);


        $query = "INSERT INTO member VALUES('$username', '$email', '$password')";
        queryMysql($query);


    }else
        {
            echo"<h1>Preecha todos os campos.</h1>";
        }


?>

<form method="post" action="register.php">
    <input type="text" name="user"  placeholder="Entre seu usuario"><br>
    <input type="text" name="email" placeholder="Entre seu email"><br>
    <input type="password" name="pass"  placeholder="Entre sua senha"><br>
    <input type="password"placeholder="Confirme sua senha"><br>
    <input type="submit" value="Sign up">
</form>

</body>
</html>
