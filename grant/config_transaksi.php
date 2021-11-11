<?php
ob_start();
include"../kabel.php";
include"config_acc_sadmn.php";
$gl_text = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$gl_panjang = strlen($gl_text);
$gl_hasil =0;
for($gl_i =1;$gl_i<=8;$gl_i++){
$gl_hasil = trim($gl_hasil).substr($gl_text,mt_rand(0,$gl_panjang),1);
}

$gl_sql_count = mysql_query("select count(kode_distributor) as akhir from gl_distributor");
$gl_sql_row= mysql_fetch_array($gl_sql_count, MYSQL_ASSOC);
$gl_num = $gl_sql_row['akhir'];
$gl_number = $gl_num+1; 
switch(strlen($gl_number)){
    case 1 : $gl_kode = "SUP0000".$gl_number; break;
    case 2 : $gl_kode = "SUP000".$gl_number; break;
    case 3 : $gl_kode = "SUP00".$gl_number; break;
    case 4 : $gl_kode = "SUP0".$gl_number; break;
        default :$gl_kode = $gl_number;
}

if(isset($_POST['id_distributor'])){
    $gl_id_dis     = strip_tags(trim($_POST['id_distributor']));
    $gl_kode  = strip_tags(trim($_POST['gl_kode_dis']));
    $gl_nama      = strip_tags(trim($_POST['gl_nama_dis']));
    $gl_alamat     = strip_tags(trim($_POST['gl_alamat_dis']));
    $gl_telp    = strip_tags(trim($_POST['gl_telp_dis']));
    $gl_gambar      = $_FILES['gl_gambar_dis']['name']?$_FILES['gl_gambar_dis']['name']:"s3.png";
    $gl_gambar_type    = pathinfo($gl_gambar, PATHINFO_EXTENSION);
    $gl_gambar_folder  = "supplier";
    $gl_gambar_new     = $gl_gambar_folder."_".$gl_hasil.".".$gl_gambar_type;
    $gl_cek_barang  = mysql_query("select * from gl_distributor where noisbn='$gl_noisbn'");
    $gl_cek_jml     = mysql_num_rows($gl_cek_barang);
    if($gl_cek_jml > 0){
        ?>
<script>alert('Supplier Dengan Kode <?php echo $gl_kode; ?> Sudah Ada !');history.back();</script>
<?php
    }else{
        if(!empty($_FILES['gl_gambar_dis']['name'])){
        $gl_simpan = mysql_query("insert into gl_distributor set id_distributor='$gl_id_dis', kode_distributor='$gl_kode', nama_distributor='$gl_nama', almat='$gl_alamat', telp='$gl_telp', gambar_distributor='$gl_gambar_new'");
        if($gl_simpan && isset($_FILES['gl_gambar_dis']['name'])){
            move_uploaded_file($_FILES['gl_gambar_dis']['tmp_name'],"pic/supplier/".$gl_gambar_new);
        }
        }else{
            $gl_simpan = mysql_query("insert into gl_distributor set id_distributor='$gl_id_dis', kode_distributor='$gl_kode', nama_distributor='$gl_nama', almat='$gl_alamat', telp='$gl_telp', gambar_distributor='$gl_gambar'");
        }
        header("location:config_supplier.php");
    }
}

$gl_limit = 10;
if(!isset($_GET['halaman'])){
    $gl_halaman = 1;
    $gl_posisi  = 0;
}else{
    $gl_halaman = $_GET['halaman'];
    $gl_posisi  = ($gl_halaman-1) * $gl_limit;
}

if(isset($_GET['sch_cari'])){
    $gl_keyword = $_GET['sch_cari'];
    $gl_sql_cari = mysql_query("select * from gl_buku where stok <=5 order by judul asc limit $gl_posisi, $gl_limit");
}else{
    $gl_sql_cari = mysql_query("select * from gl_buku where stok <=5 order by judul asc limit $gl_posisi, $gl_limit");
}
$gl_sql_cord = mysql_query("select * from gl_buku where stok <=5 order by judul asc limit $gl_posisi, $gl_limit");



if(isset($_GET['id_hps_dis'])){
    $gl_get_id = $_GET['id_hps_dis'];
    $gl_sql_get = mysql_query("select * from gl_distributor where id_distributor='$gl_get_id'");
    $gl_fetch_gambar = mysql_fetch_array($gl_sql_get);
    $gl_sql_hps = mysql_query("delete from gl_distributor where id_distributor='$gl_get_id'");
    if($gl_sql_hps){
        if(!($gl_fetch_gambar['gambar_distributor']=="s3.png")){
            unlink("pic/supplier/".$gl_fetch_gambar['gambar_distributor']);
        }
    }
    header("location:config_supplier.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/def.css" rel="stylesheet">
        <link href="pic/mimin/nicklahara.png" rel="icon">
        <title>Graylabs - Transaksi</title>
        <script>
            var i=1;
            function slide(){
                if(i==1){
                    document.getElementById('menu').style.right="0px";
                    i=2;
                }else{
                    document.getElementById('menu').style.right="-300px";
                    i=1;
                }
            }
            var h=1;
            function on(){
                if(h==1){
                    document.getElementById('msgnotice').style.display="block";
                    h=2;
                }else{
                    document.getElementById('msgnotice').style.display="none";
                    h=1;
                }
            }
        </script>
    </head>
    <body>
        <div class="msgnotice" id="msgnotice">
            <span class="glyphicon glyphicon-triangle-top"></span>
            <div class="col-md-12">
                <div class="rows">
                    <div class="col-md-12" id="not-top">
                        NOTIFIKASI
                    </div>
                </div>
                <?php 
                    $gl_sql_not = mysql_query("select * from gl_buku where stok <=5");
                    $gl_r_not = mysql_num_rows($gl_sql_not);
                if($gl_r_not > 0){
                    ?>
                <p class="big">
                    Perhatian !!!<br>
                   <b><?php echo $gl_r_not; ?> </b> Buku memiliki stok yang kurang dari 5. Mohon periksa kembali stok dan lakukan pemasokan !
                    
                </p>
                <?php
                }else{
                    ?>
                <p class="big">
                    Tidak ada notifikasi
                </p>
                <?php
                }
                    ?>
            </div>
        </div>
        <div class="profile">
            <div class="container" id="pro-fil">
                <div class="rows">
                    <div class="col-md-12">
                        <button class="btn btn-danger tip" data-toggle="tooltip" data-placement="bottom" title="Close" id="tutup-profil"><span class="glyphicon glyphicon-remove"></span></button>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-md-5">
                        <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-responsive" id="img-pro-fil">
                    </div>
                    <div class="col-md-7" id="desc-profil">
                        <div class="rows">
                        <div class="col-md-1" id="pad-top">
                            <span class="glyphicon glyphicon-user"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Nama
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $_SESSION['nama']; ?>
                        </div>
                        </div>
                        <div class="rows">
                        <div class="col-md-1" id="pad-top"> 
                            <span class="glyphicon glyphicon-map-marker"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Alamat
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $_SESSION['alamat']; ?>
                        </div>
                        </div>
                        <div class="rows">
                        <div class="col-md-1" id="pad-top">
                            <span class="glyphicon glyphicon-phone-alt"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Telepon
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $_SESSION['telepon']; ?>
                        </div>
                        </div>
                        <div class="rows">
                        <div class="col-md-1" id="pad-top">
                            <span class="glyphicon glyphicon-stats"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Status
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $_SESSION['status']; ?>
                        </div>
                        </div>
                        <div class="rows">
                        <div class="col-md-1" id="pad-top">
                            <span class="glyphicon glyphicon-asterisk"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Username
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $_SESSION['username']; ?>
                        </div>
                        </div>
                        <div class="rows">
                        <div class="col-md-1" id="pad-top">
                            <span class="glyphicon glyphicon-eye-close"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Password
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; Dienkripsi
                        </div>
                        </div>
                        <div class="rows">
                        <div class="col-md-1" id="pad-top">
                            <span class="glyphicon glyphicon-phone-alt"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Akses
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $_SESSION['akses']; ?>
                        </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="space2">
                                <a href="config_edt_profil.php?id_user=<?php echo $_SESSION['id_kasir']; ?>"><button class="btn btn-warning col-md-12 tip" data-toggle="tooltip" data-placement="bottom" title="Ubah data"><span class="glyphicon glyphicon-edit"></span> &nbsp; Ubah</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <b><a class="navbar-brand" href="config_grant.php">GRAYLABS</a></b>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div class="rows">
                    <div class="col-md-6" id="drop">
                    </div>
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-1">
                        <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-toggle tip" id="img-toggle" onclick="slide();" data-toggle="tooltip" data-placement="bottom" title="<?php echo $_SESSION['nama']; ?>">
                    </div>
                </div>       
            </div>
        </div>
    </nav>
        <?php
            if($_SESSION['akses']=="superadmin" or $_SESSION['akses']=="Manager" or $_SESSION['akses']=="Admin"){
            ?>
        <div class="menu" id="menu">
                    <div class="rows" id="bag-user">
                        <div class="col-md-3">
                            <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-toggle tip" data-toggle="tooltip" data-placement="bottom" title="<?php echo $_SESSION['nama']; ?>">
                        </div>
                        <div class="col-md-9">
                            <u><?php echo $_SESSION['nama']; ?></u> <br>
                                    <span class="middle">Akses : <?php echo $_SESSION['akses']; ?></span>
                        </div>
                    </div>
            <a href="config_grant.php"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-globe"></span>
                        </div>
                        <div class="col-md-10">
                            DASHBOARD &nbsp; <span class="badge">HOME</span>
                        </div>
                </div>
                </div></a>
            <a href="config_buku.php"><div class="rows">
                <div class="col-md-12" id="nav" >
                        <div class="col-md-2">
                            <span class="glyphicon glyphicon-book"></span>
                        </div>
                        <div class="col-md-10">
                            DATA BUKU
                        </div>
                    </div>
                </div></a>
            <?php
                if($_SESSION['akses']=="Admin"){
                    ?>
                    <a href="config_kasir.php" hidden="hidden"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2">
                            <span class="glyphicon glyphicon-user"></span>
                        </div>
                        <div class="col-md-10">
                            DATA PEGAWAI
                        </div>
                    </div>
                </div></a>
            <?php
                }else{
                    ?>
            <a href="config_kasir.php"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2">
                            <span class="glyphicon glyphicon-user"></span>
                        </div>
                        <div class="col-md-10">
                            DATA PEGAWAI
                        </div>
                    </div>
                </div></a>
            <?php
                }
                ?>
            <a href="config_supplier.php"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-th-large"></span>
                        </div>
                        <div class="col-md-10" >
                            DATA SUPPLIER
                        </div>
                </div>
                </div></a>
            <a href="config_transaksi.php"><div class="rows">
                <div class="col-md-12" id="nav" style="background: #03294A;
    color: white;
    cursor: pointer;
    border-left: solid 5px yellow;">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-th-list"></span>
                        </div>
                        <div class="col-md-10">
                            TRANSAKSI
                        </div>
                </div>
                </div></a>
            <a href="config_laporan.php"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-list-alt"></span>
                        </div>
                        <div class="col-md-10" >
                            LAPORAN
                        </div>
                    </div>
                </div></a>
        </div>
        
        <?php
            }else{
                ?>
        <div class="menu" id="menu">
                    <div class="rows" id="bag-user">
                        <div class="col-md-3">
                            <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-toggle tip" data-toggle="tooltip" data-placement="bottom" title="<?php echo $_SESSION['nama']; ?>">
                        </div>
                        <div class="col-md-9">
                            <u><?php echo $_SESSION['nama']; ?></u> <br>
                                    <span class="middle">Akses : <?php echo $_SESSION['akses']; ?></span>
                        </div>
                    </div>
            <a href="config_grant.php"><div class="rows">
                <div class="col-md-12" id="nav" style="background: #03294A;
    color: white;
    cursor: pointer;
    border-left: solid 5px yellow;">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-globe"></span>
                        </div>
                        <div class="col-md-10">
                            DASHBOARD &nbsp; <span class="badge">HOME</span>
                        </div>
                </div>
                </div></a>
            <a href="config_buku.php"><div class="rows">
                <div class="col-md-12" id="nav" >
                        <div class="col-md-2">
                            <span class="glyphicon glyphicon-book"></span>
                        </div>
                        <div class="col-md-10">
                            DATA BUKU
                        </div>
                    </div>
                </div></a>
            <a href="config_supplier.php"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-th-large"></span>
                        </div>
                        <div class="col-md-10" >
                            DATA SUPPLIER
                        </div>
                </div>
                </div></a>
            
        </div>
        <?php
            }
                ?>
        <div class="container-fluid">
            <div class="rows">
                <div class="col-md-3" id="left-content">
                    <div class="rows" >
                        <div class="col-md-12" id="pad-top">
                            <div class="rows">
                                <div class="col-md-4">
                                    <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-lc"><br><span class="small"><span class="glyphicon glyphicon-record"></span> &nbsp; Online</span>
                                </div>
                                <div class="col-md-8">
                                    <u><?php echo $_SESSION['nama']; ?></u> <br>
                                    <span class="middle">Akses : <?php echo $_SESSION['akses']; ?></span><br>
                                    
                                    <a data-toggle="modal" href="#myModal"><button type="button" class="btn tip" data-toggle="tooltip" data-placement="bottom" title="Profil" id="profil"><span class="glyphicon glyphicon-user"></span></button></a>
                                    <a href="keluar.php"><button type="button" class="btn tip" data-toggle="tooltip" data-placement="bottom" title="Logout" id="logout"><span class="glyphicon glyphicon-log-out"></span></button></a>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                     <div class="rows">
                         <div class="col-md-12" id="pad-top">
                         <a href="config_buku.php"><div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom" id="tambah" title="Data Buku">
                             <span class="glyphicon glyphicon-book"></span>
                         </div></a>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Print Data" id="print" onclick="window.print();">
                             <span class="glyphicon glyphicon-print"></span>
                         </div>
                         <a href="config_transaksi_full.php"><div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Ukuran Penuh" id="full">
                             <span class="glyphicon glyphicon-resize-full"></span>
                         </div></a>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Refresh" id="refresh" onclick="window.location.reload();">
                             <span class="glyphicon glyphicon-refresh"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Notifikasi" id="bell" onclick="on();">
                             <div class="glyphicon glyphicon-bell"></div>
                             <?php
        $gl_sql_cek_not = mysql_query("select * from gl_buku where stok <=5");
        $gl_r_num = mysql_num_rows($gl_sql_cek_not);
        if($gl_r_num > 0){
        ?>
                             <span class="badge" id="b-notice">
                                 <?php
                                 echo $gl_r_num;
                                 ?>
                             </span>
                             <?php
            }else{
            ?>
                             <span class="badge" id="b-notice" style="display:none;">
                                 
                             </span>
        <?php
        }
        ?>
                         </div>
                             </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="pad-top">
                                <div class="btn-group">
												<button type="button" class="btn btn-purple">
													Pilih Halaman
												</button>
												<button data-toggle="dropdown" class="btn btn-purple dropdown-toggle">
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
                                                    <?php
        $gl_tampil2 = mysql_query("select * from gl_buku where stok <=100");
        $gl_jumlahdata      = mysql_num_rows($gl_tampil2);
        $gl_jmlhal          = ceil($gl_jumlahdata/$gl_limit);
        for($i=1;$i<=$gl_jmlhal;$i++)
                if($i!=$gl_halaman){
                    ?>
                                                    <li><?php echo"<a href='$_SERVER[PHP_SELF]?halaman=$i'>$i</a>";  ?></li>
                                                    <?php
                    
                }
        ?>
                                                    
												</ul>
											
                            </div>
                            &nbsp; <span class="small">Jumlah Data : <?php echo $gl_jml_cord = mysql_num_rows($gl_sql_cari); ?> dari <?php echo $gl_jml_tot = mysql_num_rows($gl_sql_cord); ?></span>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="pad-top">
                                <div id="tgl"></div>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="pad-top">
                                <span class="small">Copyright @ All Right Reserved nicolahara 2017</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-9" id="right-content">
                    <div class="rows">
                        <div class="col-md-12" id="banner">
                            <span class="glyphicon glyphicon-th-list"></span> &nbsp; Transaksi & Kendali Stok
                        </div>
                    </div>
                    <div class="rows">
                        <a href="config_transaksi_import.php" style="color:black"><div class="col-md-3">
                            <div class="rows">
                                <div class="col-md-12 menu-transaksi" id="transaksi-satu">
                                    <div class="col-md-3" id="blue-trans"><span class="glyphicon glyphicon-plus" id="plus"></span></div>
                                    <div class="col-md-9" id="white-trans">
                                        <div class="col-md-12" id="white-top">
                                            Pasok buku
                                        </div>
                                        <div class="col-md-12" id="white-bottom">
                                            <span class="glyphicon glyphicon-home"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-chevron-left"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-home"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                        <a href="config_transaksi_eksport.php" style="color:black;"><div class="col-md-3">
                            <div class="rows">
                                <div class="col-md-12 menu-transaksi" id="transaksi-dua">
                                    <div class="col-md-3" id="red-trans"><span class="glyphicon glyphicon-plus" id="plus"></span></div>
                                   <div class="col-md-9" id="white-trans">
                                        <div class="col-md-12" id="white-top">
                                            Penjualan Buku
                                        </div>
                                        <div class="col-md-12" id="white-bottom">
                                            <span class="glyphicon glyphicon-home"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-chevron-right"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                        <a href="config_transaksi_retur_eksport.php" style="color:black;"><div class="col-md-3">
                            <div class="rows">
                                <div class="col-md-12 menu-transaksi" id="transaksi-tiga">
                                    <div class="col-md-3" id="green-trans"><span class="glyphicon glyphicon-plus" id="plus"></span></div>
                                    <div class="col-md-9" id="white-trans">
                                        <div class="col-md-12" id="white-top">
                                            Retur Buku ke Supplier
                                        </div>
                                        <div class="col-md-12" id="white-bottom">
                                            <span class="glyphicon glyphicon-book"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-chevron-right"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-home"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                        <a href="config_transaksi_retur_import.php" style="color:black;"><div class="col-md-3">
                            <div class="rows">
                                <div class="col-md-12 menu-transaksi" id="transaksi-empat">
                                    <div class="col-md-3" id="yellow-trans"><span class="glyphicon glyphicon-plus" id="plus"></span></div>
                                    <div class="col-md-9" id="white-trans">
                                        <div class="col-md-12" id="white-top">
                                            Retur Buku dari Konsumen
                                        </div>
                                        <div class="col-md-12" id="white-bottom">
                                            <span class="glyphicon glyphicon-book"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-chevron-left"></span> &nbsp; 
                                            <span class="glyphicon glyphicon-user"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="banner-sec">
                            <span class="glyphicon glyphicon-list-alt"></span> &nbsp; Data Stok Buku -100
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>NO.</th>
                            <th>Cover</th>
                            <th>Judul</th>
                            <th>ISBN</th>
                            <th>Penulis</th>
                            <th>Stok</th>
                            <th>Option</th>
                        </tr>
                        <?php
                        $gl_jml_data = mysql_num_rows($gl_sql_cari);
                        if($gl_jml_data >0){
                            $gl_no =1;
                            while ($gl_fetch = mysql_fetch_array($gl_sql_cari)){
                                if($gl_no%2==0){
                                    $gl_warna = "#f8f8f8";
                                }else{
                                    $gl_warna ="white";
                                }
                            
                        ?>
                        <tr bgcolor="<?php echo $gl_warna; ?>">
                            <td><?php echo $gl_no; ?></td>
                            <td><img src="../pic/book/<?php echo $gl_fetch['gambar_buku']; ?>" class="img-cover"></td>
                            <td><?php echo $gl_fetch['judul']; ?></td>
                            <td><?php echo $gl_fetch['noisbn']; ?></td>
                            <td><?php echo $gl_fetch['penulis']; ?></td>
                            <td><?php echo $gl_fetch['stok']; ?></td>            
                            <td><a href="config_transaksi_import_short.php?id_buku=<?php echo $gl_fetch['id_buku']; ?>"><button type="button" class="btn" id="green"><span class="glyphicon glyphicon-plus"></span></button></a></td>
                        </tr>
                        <?php
                                $gl_no++;
                                }
                        }else{
                            ?>
                        <tr>
                            <td colspan="9" align="center"> Data Tidak Ditemukan</td>
                        </tr>
                        <?php
                        }
                                ?>
                    </table>
                </div>
            </div> 
        </div>
        <script src="js/jquery-2.1.4.js" type="text/javascript"></script>
        <script src="js/bootstrap.js" type="text/javascript"></script>
        <script src="../js/config_default.js" type="text/javascript"></script>
        <script type="text/javascript">
			$(document).ready(function () {
				$(".tip").tooltip();
			});
		</script>
    </body>
</html>
<?php
mysql_close($gl_koneksi);
ob_flush();
?>