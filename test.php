<?php
include_once 'functions.php';

$st = "ola, meu nome eh fagern nunes";
$st2 =encrypt($st);
echo "$st2";
$st2= decrypt($st2);
echo "$st2";
?>