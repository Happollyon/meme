<?php
$dbhost = "localhost";
$dbname = "meme";
$dbuser = "root";
$dbpass="" ;

$connection = new mysqli($dbhost, $dbuser,$dbpass , $dbname);  //prepares a connection
if($connection->error) die($connection->connect_error);         // if there is an error


function queryMysql($query)   // function to query the database
{
    global $connection;
    $result = $connection->query($query);
    if(!$result) die($connection->error);
    return $result;
}

function destroSection() // end section
{
    $_SESSION=array();
    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(),'',time()-2592000,'/');
    session_destroy();
}

function sanit($var) // sanitizes data that enters the database
{
    global $connection;
    $var =strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}
function encrypt($string) // Function used to encrypt data which goes over GET request  (OpenSSL)
{
    $cipher = "aes-128-gcm";
    $key = "FaGnErNuNeS9946";
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $ciphertext_raw = openssl_encrypt($string, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
   return urlencode( $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw ));


}
function decrypt($string) // Function which decrypts data
{   $key = "FaGnErNuNeS9946";
    $cipher = "aes-128-gcm";
    $c = base64_decode($string);
    $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len=32);
    $ciphertext_raw = substr($c, $ivlen+$sha2len);
  return  $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
    $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);


}

?>