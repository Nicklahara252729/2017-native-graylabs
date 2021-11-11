<?php
ob_start();
if(!isset($_SESSION['username'])){
    session_start();
}

$gl_akses = $_SESSION['akses'];
unset($_SESSION['nama']);
unset($_SESSION['alamat']);
unset($_SESSION['telepon']);
unset($_SESSION['status']);
unset($_SESSION['username']);
unset($_SESSION['password']);
unset($_SESSION['akses']);
unset($_SESSION['gambar_kasir']);
session_destroy();
if($gl_akses=="superadmin"){
    header("location:index.php");
}else if($gl_akses=="admin"){
    header("location:index.php");
}else{
    header("location:index.php");
}
ob_flush();
?>