<?php
session_start();

if(isset($_SESSION['views']['test']))
$_SESSION['views']['test']=$_SESSION['views']['test'] + 1 ;
else
$_SESSION['views']['test']=1;
echo "Views=". $_SESSION['views']['test'];
?> 