<?php
ob_start();
include"../kabel.php";
include"config_acc_sadmn.php";


$gl_sql_report_beli = mysql_query("select * from gl_pasok join gl_detailpasok on (gl_pasok.id_pasok=gl_detailpasok.id_pasok) order by gl_pasok.id_pasok desc limit 10");
$gl_sql_report_jual = mysql_query("select * from gl_penjualan join gl_detailjual on (gl_penjualan.id_penjualan=gl_detailjual.id_penjualan) order by gl_penjualan.id_penjualan desc limit 10");
$gl_sql_report_laba = mysql_query("select * from gl_buku join gl_distributor on(gl_buku.id_distributor=gl_distributor.id_distributor) order by gl_buku.judul desc limit 10");
$gl_sql_report_kasir = mysql_query("select * from gl_kasir  order by gl_kasir.id_kasir desc limit 10");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/def.css" rel="stylesheet">
        <link href="pic/mimin/nicklahara.png" rel="icon">
        <title>Graylabs - Laporan</title>
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
                <div class="col-md-12" id="nav">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-th-list"></span>
                        </div>
                        <div class="col-md-10">
                            TRANSAKSI
                        </div>
                </div>
                </div></a>
            <a href="config_laporan.php"><div class="rows">
                <div class="col-md-12" id="nav" style="background: #03294A;
    color: white;
    cursor: pointer;
    border-left: solid 5px yellow;">
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
            <a href="config_grant.php"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2" >
                            <span class="glyphicon glyphicon-th-list"></span>
                        </div>
                        <div class="col-md-10">
                            TRANSAKSI
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
                             <a href="config_transaksi.php">
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Transaksi" id="print">
                             <span class="glyphicon glyphicon-th-list"></span>
                         </div>
                                 </a>
                         <a href="config_grant.php"><div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Home" id="full">
                             <span class="glyphicon glyphicon-home"></span>
                         </div></a>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="HOe" id="refresh" onclick="window.location.reload();">
                             <span class="glyphicon glyphicon-refresh"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Notifikasi" id="bell" onclick="on();">
                             <span class="glyphicon glyphicon-bell"></span>
                             <?php
        $gl_sql_cek_not = mysql_query("select * from gl_buku where stok <=5");
        $gl_r_num = mysql_num_rows($gl_sql_cek_not);
        if($gl_r_num > 0){
        ?>
                             <span class="badge" id="b-notice" style="margin-top:-40px;margin-left:10px;">
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
                            <span class="glyphicon glyphicon-list-alt"></span> &nbsp; Laporan
                        </div>
                    </div>
                    <div class="col-md-12" style="padding:0;margin-bottom:20px;">
                    <div class="rows">
                        <div class="col-md-6" >
                            <div class="rows">
                                <div class="col-md-12" id="report">
                                        <div class="col-md-12" id="blue-report">
                                            <div class="col-md-4">
                                                <div class="icon-report">
                                                    <span class="glyphicon glyphicon-shopping-cart"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-8" id="report-txt">
                                                Laporan Pembelian dari Supplier
                                            </div>
                                        </div>
                                    <div class="col-md-12" id="white-report">                                                          <div class="rows">
                                            <div class="col-md-4" style="border-right:solid 1px lightgray;padding:0;text-align:center;">
                                                <?php
                                                $gl_sql_tot_beli = mysql_query("select * from gl_pasok");
                                                $gl_cek_jml_tot_beli = mysql_num_rows($gl_sql_tot_beli);
                                                if($gl_cek_jml_tot_beli){
                                                    $gl_tot_beli = 0;
                                                    while($gl_r_tot_beli = mysql_fetch_array($gl_sql_tot_beli)){
                                                        $gl_tot_beli = $gl_r_tot_beli['jumlah']+$gl_tot_beli;
                                                    }
                                                    echo"Rp ".number_format($gl_tot_beli,0,',','.');
                                                }else{
                                                    echo"Rp 0" ;
                                                }
                                                ?><br>
                                                Total Pembelian
                                            </div>
                                            <div class="col-md-4" style="border-right:solid 1px lightgray;padding:0;text-align:center;">
                                                <?php
                                                $gl_sql_tot_barang = mysql_query("select * from gl_detailpasok");
                                                $gl_cek_jml_barang = mysql_num_rows($gl_sql_tot_barang);
                                                if($gl_cek_jml_barang > 0){
                                                    $gl_tot_barang =0;
                                                    while($gl_r_tot_barang = mysql_fetch_array($gl_sql_tot_barang)){
                                                        $gl_tot_barang = $gl_r_tot_barang['jml_beli'] + $gl_tot_barang;
                                                    }
                                                    echo $gl_tot_barang;
                                                }
                                                ?>
                                                <br>
                                                Total Barang
                                            </div>
                                        <div class="col-md-4" style="padding:0;text-align:center;">
                                                <?php
                                            $gl_sql_tot_tb = mysql_query("select * from gl_pasok");
                                            echo $gl_cek_jml_tb=mysql_num_rows($gl_sql_tot_tb);
                                            ?><br>
                                                Total Transaksi
                                            </div>
                                        </div>
                                        <?php
                                        $gl_cek_jml_beli = mysql_num_rows($gl_sql_report_beli);
                                        if($gl_cek_jml_beli > 0){
                                            while($gl_r_beli = mysql_fetch_array($gl_sql_report_beli)){
                                                
                                            
                                        ?>
                                        <div class="rows">
                                            <div class="col-md-9" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                               <span class="small"> Kode Transaksi : <?php echo $gl_r_beli['id_pasok']; ?></span> |
                                               <span class="small"> Tanggal : <?php echo substr($gl_r_beli['tanggal'],0,10); ?></span><br>
                                               <span class="small"> Jumlah Beli : <?php echo $gl_r_beli['jml_beli']; ?></span> | 
                                               <span class="small"> Total : <?php echo"Rp ".number_format($gl_r_beli['jumlah'],0,',','.'); ?></span>
                                            </div>
                                            <div class="col-md-3" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                                <a href="config_report_beli.php?id_pasok=<?php echo $gl_r_beli['id_pasok']; ?>"><button type="button" class="btn btn-blue tip"  data-toggle="tooltp" data-placement="bottom" title="View"><span class="glyphicon glyphicon-open-file"></span></button></a>
                                            </div>
                                        </div>
                                        <?php
                                                }
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12" style="background:white;height:10%">
                                        <a href="config_report_beli_full.php"><div class=" btn-blue">Lihat Semua</div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <div class="rows">
                                <div class="col-md-12" id="report">
                                        <div class="col-md-12" id="green-report">
                                            <div class="col-md-4">
                                                <div class="icon-report">
                                                    <span class="glyphicon glyphicon-tags"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-8" id="report-txt">
                                                Laporan Penjualan ke Konsumen
                                            </div>
                                        </div>
                                    <div class="col-md-12" id="white-report">                                                          <div class="rows">
                                            <div class="col-md-4" style="border-right:solid 1px lightgray;padding:0;text-align:center;">
                                                <?php
                                                $gl_sql_tot_beli = mysql_query("select * from gl_penjualan");
                                                $gl_cek_jml_tot_beli = mysql_num_rows($gl_sql_tot_beli);
                                                if($gl_cek_jml_tot_beli){
                                                    $gl_tot_beli = 0;
                                                    while($gl_r_tot_beli = mysql_fetch_array($gl_sql_tot_beli)){
                                                        $gl_tot_beli = $gl_r_tot_beli['jumlah']+$gl_tot_beli;
                                                    }
                                                    echo"Rp ".number_format($gl_tot_beli,0,',','.');
                                                }else{
                                                    echo"Rp 0" ;
                                                }
                                                ?><br>
                                                Total Penjualan
                                            </div>
                                            <div class="col-md-4" style="border-right:solid 1px lightgray;padding:0;text-align:center;">
                                                <?php
                                                $gl_sql_tot_barang = mysql_query("select * from gl_detailjual");
                                                $gl_cek_jml_barang = mysql_num_rows($gl_sql_tot_barang);
                                                if($gl_cek_jml_barang > 0){
                                                    $gl_tot_barang =0;
                                                    while($gl_r_tot_barang = mysql_fetch_array($gl_sql_tot_barang)){
                                                        $gl_tot_barang = $gl_r_tot_barang['jml_beli'] + $gl_tot_barang;
                                                    }
                                                    echo $gl_tot_barang;
                                                }
                                                ?>
                                                <br>
                                                Terjual
                                            </div>
                                        <div class="col-md-4" style="padding:0;text-align:center;">
                                                <?php
                                            $gl_sql_tot_tb = mysql_query("select * from gl_penjualan");
                                            echo $gl_cek_jml_tb=mysql_num_rows($gl_sql_tot_tb);
                                            ?><br>
                                                Total Transaksi
                                            </div>
                                        </div>
                                        <?php
                                        $gl_cek_jml_jual = mysql_num_rows($gl_sql_report_jual);
                                        if($gl_cek_jml_jual > 0){
                                            while($gl_r_jual = mysql_fetch_array($gl_sql_report_jual)){
                                                
                                            
                                        ?>
                                        <div class="rows">
                                            <div class="col-md-9" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                               <span class="small"> Kode Transaksi : <?php echo $gl_r_jual['id_penjualan']; ?></span> |
                                               <span class="small"> Tanggal : <?php echo substr($gl_r_jual['tanggal'],0,10); ?></span><br>
                                               <span class="small"> Jumlah Jual : <?php echo $gl_r_jual['jml_beli']; ?></span> | 
                                               <span class="small"> Total : <?php echo"Rp ".number_format($gl_r_jual['jumlah'],0,',','.'); ?></span>
                                            </div>
                                            <div class="col-md-3" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                                <a href="config_report_jual.php?id_pasok=<?php echo $gl_r_jual['id_penjualan']; ?>"><button type="button" class="btn btn-green tip"  data-toggle="tooltp" data-placement="bottom" title="View"><span class="glyphicon glyphicon-open-file"></span></button></a>
                                            </div>
                                        </div>
                                        <?php
                                                }
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12" style="background:white;height:10%">
                                        <a href="config_report_jual_full.php"><div class=" btn-green">Lihat Semua</div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-12" style="padding:0;margin-bottom:20px;">
                    <div class="rows">
                        <div class="col-md-6" >
                            <div class="rows">
                                <div class="col-md-12" id="report">
                                        <div class="col-md-12" id="kuning-report">
                                            <div class="col-md-4">
                                                <div class="icon-report">
                                                    <span class="glyphicon glyphicon-euro"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-8" id="report-txt">
                                                Laporan Laba / Keuntungan
                                            </div>
                                        </div>
                                    <div class="col-md-12" id="white-report">                                                          <div class="rows">
                                            <div class="col-md-12" style="padding:0;text-align:center;">
                                                <?php
                                                $gl_sql_tot_beli = mysql_query("select * from gl_penjualan");
                                                $gl_cek_jml_tot_beli = mysql_num_rows($gl_sql_tot_beli);
                                                if($gl_cek_jml_tot_beli){
                                                    $gl_tot_beli = 0;
                                                    while($gl_r_tot_beli = mysql_fetch_array($gl_sql_tot_beli)){
                                                        $gl_tot_beli = $gl_r_tot_beli['jumlah']+$gl_tot_beli;
                                                    }
                                                    echo"Rp ".number_format($gl_tot_beli,0,',','.');
                                                }else{
                                                    echo"Rp 0" ;
                                                }
                                                ?><br>
                                                Total Laba
                                            </div>
                                            
                                        
                                        </div>
                                        <?php
                                        $gl_cek_jml_laba = mysql_num_rows($gl_sql_report_laba);
                                        if($gl_cek_jml_laba > 0){
                                            while($gl_r_laba = mysql_fetch_array($gl_sql_report_laba)){
                                                
                                            
                                        ?>
                                        <div class="rows">
                                            <div class="col-md-9" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                               <span class="small"> Judul : <?php echo $gl_r_laba['judul']; ?></span> <br>
                                               <span class="small"> Penerbit: <?php echo $gl_r_laba['nama_distributor']; ?></span><br>
                                            </div>
                                            <div class="col-md-3" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                                <a href="config_report_laba.php?id_pasok=<?php echo $gl_r_laba['id_buku']; ?>"><button type="button" class="btn btn-kuning tip"  data-toggle="tooltp" data-placement="bottom" title="View"><span class="glyphicon glyphicon-open-file"></span></button></a>
                                            </div>
                                        </div>
                                        <?php
                                                }
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12" style="background:white;height:10%">
                                        <a href="config_report_laba_full.php"><div class=" btn-kuning">Lihat Semua</div></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6" >
                            <div class="rows">
                                <div class="col-md-12" id="report">
                                        <div class="col-md-12" id="red-report">
                                            <div class="col-md-4">
                                                <div class="icon-report">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-8" id="report-txt">
                                                Laporan Perpegawai
                                            </div>
                                        </div>
                                    <div class="col-md-12" id="white-report">                                                          <div class="rows">
                                            <div class="col-md-6" style="border-right:solid 1px lightgray;padding:0;text-align:center;">
                                                <?php
                                                $gl_sql_tot_kasir = mysql_query("select * from gl_kasir");
                                                $gl_cek_jml_tot_kasir = mysql_num_rows($gl_sql_tot_kasir);
                                                if($gl_cek_jml_tot_kasir){
                                                    $gl_tot_kasir = 0;
                                                    while($gl_r_tot_kasir = mysql_fetch_array($gl_sql_tot_kasir)){
                                        
                                                    }
                                                    echo $gl_cek_jml_tot_kasir;
                                                }else{
                                                    echo"Rp 0" ;
                                                }
                                                ?><br>
                                                Pegawai
                                            </div>
                                        <div class="col-md-6" style="padding:0;text-align:center;">
                                                <?php
                                            $gl_sql_tot_kasirtb = mysql_query("select * from gl_pasok");
                                            $gl_sql_tot_kasirtb2 = mysql_query("select * from gl_penjualan");
echo $gl_kasir_transaksi =  $gl_cek_jml_tb=mysql_num_rows($gl_sql_tot_kasirtb) + $gl_cek_jml_tb2=mysql_num_rows($gl_sql_tot_kasirtb2);
                                            ?><br>
                                                Total Transaksi
                                            </div>
                                        </div>
                                        <?php
                                        $gl_cek_jml_kasir = mysql_num_rows($gl_sql_report_kasir);
                                        if($gl_cek_jml_kasir > 0){
                                            while($gl_r_kasir = mysql_fetch_array($gl_sql_report_kasir)){
                                                
                                            
                                        ?>
                                        <div class="rows">
                                            <div class="col-md-9" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                               <span class="small"> Nama Kasir : <?php echo $gl_r_kasir['nama']; ?></span> <br>
                                                <span class="small"> Username : <?php echo $gl_r_kasir['username']; ?></span> 


                                            </div>
                                            <div class="col-md-3" style="border-top:solid 1px lightgray;padding-top:5px;margin-top:10px;">
                                                <a href="config_report_kasir.php?id_pasok=<?php echo $gl_r_kasir['id_kasir']; ?>"><button type="button" class="btn btn-red tip"  data-toggle="tooltp" data-placement="bottom" title="View"><span class="glyphicon glyphicon-open-file"></span></button></a>
                                            </div>
                                        </div>
                                        <?php
                                                }
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-12" style="background:white;height:10%">
                                        <a href="config_report_kasir_full.php"><div class=" btn-red">Lihat Semua</div></a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </div>
                </div>
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
