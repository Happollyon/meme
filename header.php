<?php
include_once 'functions.php';


session_start();
if(isset($_SESSION['user']))
{
    $user = $_SESSION['user'];
    $loggedin= TRUE;

}else $loggedin=FALSE;
?>
<!DOCTYPE HTML>
<html lang='pt'>
<head>
    <title>meme.com.br</title>
   <link rel="stylesheet" href="style/header.css">
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans|Montserrat|Press+Start+2P&display=swap" rel="stylesheet">

</head>

<body>
<div id="cont">
<?php
if(!$loggedin)
{
    echo"
    <div id='menu'>


                <div id='memes'>
                <a href='index.php'><img src='images/meme3.png'></a>
                </div>
            <div id='menu1'>


            <div id='log'> <a href='login.php'>login </a> </div>


            <div id='sing'><a href='register.php'> Sign up</a></div>
            </div>



    </div>";


}
else{

    echo"<div id='menu'>


                <div id='memes'>
                <img src='images/meme3.png'>
                </div>
        <div id='menu2'>

            <div id='search'>
            
            <form autocomplete='off'>
            <input autocomplete='off' type='search' placeholder='procurar' name='procurar'>
            </form>
            
            </div>
            <div id='hot'> <a>Hot </a> </div>


            <div id='perfil'><a href='main.php'>main</a></div>
            <div id='sair'><a href='exit.php'>Sair</a></div>
        </div>
</div>
";

}
?>

