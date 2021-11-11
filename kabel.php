<?php
$gl_koneksi = mysql_connect('localhost','root','') or die("koneksi gagal");
$db = mysql_select_db('graylabsdb',$gl_koneksi) or die("database error");
?>