<?php
ob_start();
include"../kabel.php";
if(!isset($_SESSION['username'])){
    session_start();
}

if(isset($_SESSION['username'])){
    header("location:config_grant.php");
}


$msg_login ="Masukkan Username dan Password Anda";
if(isset($_POST['username'])){
    $gl_username =  strip_tags(trim($_POST['username']));
    $gl_password =  strip_tags(trim($_POST['password']));
    $gl_password1 = sha1($gl_password);
    if(isset($_POST['remember'])){
        setcookie("username",$gl_username,time() + (3600 * 24));
        setcookie("password",$gl_password,time() + (3600 * 24));
    }else{
        unset($_COOKIE['username']);
        unset($_COOKIE['password']);
    }
    
    $gl_sql_cek = mysql_query("select * from gl_kasir where username='$gl_username' and password='$gl_password1'");
    $gl_jml_cek = mysql_num_rows($gl_sql_cek);
    $gl_fetch_cek = mysql_fetch_array($gl_sql_cek);
    if($gl_jml_cek > 0){
        $_SESSION['id_kasir'] = $gl_fetch_cek['id_kasir'];
        $_SESSION['nama']     = $gl_fetch_cek['nama'];
        $_SESSION['alamat']   = $gl_fetch_cek['alamat'];
        $_SESSION['telepon']  = $gl_fetch_cek['telepon'];
        $_SESSION['status']   = $gl_fetch_cek['status'];
        $_SESSION['username'] = $gl_fetch_cek['username'];
        $_SESSION['password'] = $gl_fetch_cek['password'];
        $_SESSION['akses']    = $gl_fetch_cek['akses'];
        $_SESSION['gambar_kasir'] = $gl_fetch_cek['gambar_kasir'];
            header("location:config_grant.php");
    }else{
        $msg_login="Username dan Password salah !";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/def.css" rel="stylesheet">
        <script src="js/jquery-2.1.4.js" type="text/javascript"></script>
        <link href="pic/mimin/nicklahara.png" rel="icon">
        <title>Graylabs - Welcome</title>
    </head>
    <body>
        <div class="
top"><span class="glyphicon glyphicon-chevron-up"></span></div>
        <div class="jumbotron">
            <div class="container-fluid" id="in-jumbo">
                <div class="container" id="log-in">
                <div class="col-md-12">
                    <div class="img-login"></div>
                    </div>
                <div class="col-md-12">
                    <div class="errorHandler alert alert-danger no-display">
							<i class="glyphicon glyphicon-info-sign"></i> <?php echo $msg_login; ?>
						</div>
                
                </div>
                <div class="col-md-12" id="white">
                    <form target="_self" enctype="multipart/form-data" method="post" name="login" >
                    <div class="rows" id="space">
                        
                        <div class="col-md-1" id="in-space" style="border:solid 1px lightgray;padding:0;text-align:center;padding-top:10px;background:#f8f8f8;">
                            <span class="glyphicon glyphicon-user"></span>
                        </div>
                        <div class="col-md-11" id="in-space" style="padding:0;border-top:solid 1px lightgray;border-right:solid 1px lightgray;">
                            <input type="text" placeholder="Username" name="username" id="username" required class="col-md-12" value="<?php echo isset($_COOKIE['username'])?$_COOKIE['username']:''; ?>">
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space" style="border:solid 1px lightgray;padding:0;text-align:center;padding-top:10px;background:#f8f8f8;">
                            <span class="glyphicon glyphicon-lock"></span>
                        </div>
                        <div class="col-md-11" id="in-space" style="padding:0;border-top:solid 1px lightgray;border-right:solid 1px lightgray;">
                            <input type="password" placeholder="Password" name="password" id="password" required class="col-md-12" value="<?php echo isset($_COOKIE['password'])?$_COOKIE['password']:''; ?>">
                        </div>
                    </div>
                    <div class="rows">
                        
                        <div class="col-md-12">
                            <input type="checkbox" name="remember" id="remember"> Remember Me
                        </div>
                    </div>
                    <div class="rows" id="space2" style="margin-top:50px;">
                        
                            <button type="submit" class="btn btn-primary col-md-12"><span class="glyphicon glyphicon-send" style="height:30px;line-height:25px;"></span> &nbsp; Log in</button>
                            
                        
                    </div>
                    </form>
                </div>
            </div>
                </div>
        </div>
    </body>
</html>
<?php
mysql_close($gl_koneksi);
ob_flush();
?>