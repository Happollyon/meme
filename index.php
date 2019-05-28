<?php
require_once 'header.php';

?>
<link href="style/index.css" rel="stylesheet">
<div id="signup">


    <form method="post" action="register.php" onsubmit="stop()">
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
<div id="texto">
    Junte-se e ganhe <br>reconhecimento pelos <br>seus memes.
</div>
<div id="imagem">
    <img src="images/cc4.jpeg">

</div>

</div>

</body>
</html>
