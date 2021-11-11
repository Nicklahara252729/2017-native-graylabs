<?php
ob_start();
if(!isset($_SESSION['username'])){
    session_start();
}

if(!isset($_SESSION['username'])){
    header("location:404.php?Akses=Superadmin1");
}
ob_flush();    
?>