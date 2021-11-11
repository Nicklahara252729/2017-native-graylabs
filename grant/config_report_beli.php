<?php
ob_start();
if((empty($_GET['destroy'])==FALSE)){
    session_destroy();
}
include"../kabel.php";
include"config_acc_sadmn.php";


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
    $gl_sql_cari = mysql_query("select * from gl_buku");
}else{
    $gl_sql_cari = mysql_query("select * from gl_buku");
}
$gl_sql_cord = mysql_query("select * from gl_buku");

if(isset($_GET['gl_id_pasok'])){
    $gl_get_pasok = strip_tags(trim($_GET['gl_id_pasok']));
$gl_show_transit = mysql_query("select * from gl_transit where id_pasok='$gl_get_pasok'");    
}

if(isset($_GET['id_pasok'])){
$gl_get_id_pasok = $_GET['id_pasok'];
$gl_sql_report = mysql_query("select * from gl_detailpasok join gl_distributor on (gl_detailpasok.id_distributor=gl_distributor.id_distributor) join gl_pasok on (gl_detailpasok.id_pasok=gl_pasok.id_pasok) join gl_buku on (gl_detailpasok.id_buku=gl_buku.id_buku) where gl_detailpasok.id_pasok='$gl_get_id_pasok'");
$gl_sql_report2 = mysql_query("select * from gl_detailpasok join gl_distributor on (gl_detailpasok.id_distributor=gl_distributor.id_distributor) join gl_pasok on (gl_detailpasok.id_pasok=gl_pasok.id_pasok) join gl_buku on (gl_detailpasok.id_buku=gl_buku.id_buku) join gl_kasir on (gl_pasok.id_kasir=gl_kasir.id_kasir) where gl_detailpasok.id_pasok='$gl_get_id_pasok'");
}else{
    $gl_sql_report = mysql_query("select * from gl_detailpasok join gl_distributor on (gl_detailpasok.id_distributor=gl_distributor.id_distributor) join gl_pasok on (gl_detailpasok.id_pasok=gl_pasok.id_pasok) join gl_buku on (gl_detailpasok.id_buku=gl_buku.id_buku) where gl_detailpasok.id_pasok=''");
$gl_sql_report2 = mysql_query("select * from gl_detailpasok join gl_distributor on (gl_detailpasok.id_distributor=gl_distributor.id_distributor) join gl_pasok on (gl_detailpasok.id_pasok=gl_pasok.id_pasok) join gl_buku on (gl_detailpasok.id_buku=gl_buku.id_buku) join gl_kasir on (gl_pasok.id_kasir=gl_kasir.id_kasir) where gl_detailpasok.id_pasok=''");
}
    $gl_r=mysql_fetch_array($gl_sql_report2);    


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/def.css" rel="stylesheet">
        <link href="pic/mimin/nicklahara.png" rel="icon">
        <title>Graylabs - Laporan Pembelian</title>
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
        </script>
    </head>
    <body>
        <?php
        
        ?>
        <div class="full">
            <div class="container-fluid">
                <div class="rows">
                    <div class="col-md-12" id="content-full2">
                        <div class="rows">
                            <div class="col-md-12" id="gray-report">
                                <div class="rows">
                                    <div class="col-md-4" id="pad-top2">
                                        
                                            <div class="col-md-9" style="padding:0;">

                                            </div>
                                            <div class="col-md-3">
                         <div class="report-logo"></div></div>
                    
                                    </div>
                                    <div class="col-md-4" id="search2" align="center">
                                        <h4>GRAYLABS BOOK STORE<br>LAPORAN PEMBELIAN BUKU<br><span class="small">Jl.Patriot No 11 KM 7.5 MEDAN - SUMATRA UTARA</span></h4>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="report-logo2"></div>
                                        <a href="config_laporan.php"><button class="btn btn-danger tip" data-toggle="tooltip" data-placement="bottom" title="Close" id="tutup-full"><span class="glyphicon glyphicon-remove"></span></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12">
                                <div class="col-md-2" style="padding:0;">
                                    Kode Transaksi  
                                </div>
                                <div class="col-md-1" style="padding:0;width:10px;">:</div>
                                <div class="col-md-5" style="padding:0;text-align:left;">
                                <?php echo $gl_r['id_pasok']; ?></div>
                            </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12">
                                <div class="col-md-2" style="padding:0;">
                                    Tanggal 
                                </div>
                                <div class="col-md-1" style="padding:0;width:10px;">:</div>
                                <div class="col-md-5" style="padding:0;text-align:left;">
                                <?php

                                                        echo substr($gl_r['tanggal'],0,10);
                                                        ?></div>
                            </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12">
                                <div class="col-md-2" style="padding:0;margin-bottom:10px;">
                                    Waktu 
                                </div>
                                <div class="col-md-1" style="padding:0;width:10px;margin-bottom:10px;">:</div>
                                <div class="col-md-9" style="padding:0;text-align:left;margin-bottom:10px;">
                                <?php
                                                        echo substr($gl_r['tanggal'],11,18);
                                                        ?>
                                    <button style="float:right;" type="button" class="btn btn-purple tip" data-toggle="tooltip" data-placement="bottom" onclick="window.print();" title="Print"><span class="glyphicon glyphicon-print" onclick="window.print();"></span></button>
                                </div>
                            </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="report-table">
                                <table class="table table-striped" style="box-shadow:0px 0px 0px 0px;border:solid 1px lightgray;">
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul Buku</th>
                                        <th>ISBN</th>
                                        <th>Penerbit</th>
                                        <th style="text-align:center;">Count</th>
                                        <th style="text-align:right;">Harga Pokok</th>
                                        <th style="text-align:right;">Total / Item</th>
                                    </tr>
                                    <?php
                                    $gl_rows = mysql_num_rows($gl_sql_report);
                                    $gl_total = 0;
                                    if($gl_rows > 0){
                                        $gl_no =1;
                                        
                                        while($gl_fetch = mysql_fetch_array($gl_sql_report)){
                                        $gl_tot = $gl_fetch['harga_pokok']*$gl_fetch['jml_beli'];
                                        $gl_total = $gl_total + $gl_tot;
                                     
                                    ?>
                                    <tr>
                                        <td><?php echo $gl_no; ?></td>
                                        <td><?php echo $gl_fetch['judul']; ?></td>
                                        <td><?php echo $gl_fetch['noisbn']; ?></td>
                                        <td><?php echo $gl_fetch['nama_distributor']; ?></td>
                                        
                                        <td align="center"><?php echo $gl_fetch['jml_beli']; ?> &nbsp; Unit</td>
                                        <td align="right"><?php echo"Rp ".number_format($gl_fetch['harga_pokok'],0,',','.'); ?></td>
                                        <td align="right"><?php echo"Rp ".number_format($gl_fetch['harga_pokok']*$gl_fetch['jml_beli'],0,',','.'); ?></td>
                                    </tr>
                                    <?php
                                            $gl_no++;
                                       }
                                    }else{
                                        ?>
                                    <tr>
                                        <td colspan="8" align="center">DATA TIDAK DITEMUKAN</td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="6" align="center" style="border-right:solid 1px lightgray;">TOTAL SELURUH</td>
                                        <td align="right"><b><?php echo"Rp ".number_format($gl_total,0,',','.');  ?></b></td>
                                    </tr>
                                </table>
                            </div>
                </div>
                        <div class="rows">
                            <div class="col-md-12">
                                Petugas :<br>
                                <b><?php
                                echo $gl_r['nama'];
                                ?></b><br><br>
                                .......................
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
                         <form name="cari" id="cari" enctype="multipart/form-data" method="get" target="_self">
							  <input type="text" class="col-md-10" id="inpt_sch" placeholder="Cari Judul, ISBN, Penulis, Penerbit, Tahun" name="sch_cari">
							  <button type="submit" class="btn btn-primary col-md-2" id="purple"><span class="glyphicon glyphicon-search"></span></button>
							</form>
                    </div>
                    <div class="col-md-1">
                        <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-toggle" id="img-toggle" onclick="slide();">
                    </div>
                </div>       
            </div>
        </div>
    </nav>
        <div class="menu" id="menu">
                <div class="col-md-12" >
                    <div class="rows" id="bag-user">
                        <div class="col-md-3">
                            <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-toggle">
                        </div>
                        <div class="col-md-9">asd</div>
                    </div>
                    <div class="rows">
                <div class="col-md-12">
                    <div class="navbar">
                        <ul class="nav">
                            <a href=""><li><span class="glyphicon glyphicon-user"></span> &nbsp; Konsumen</li></a>
                            <li>asda</li>
                            <li>asda</li>
                            <li>asda</li>
                            <li>asda</li>
                        </ul>
                    </div>
                </div>
            </div>
                </div>
        </div>
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
                         <a href="config_supplier_full.php"><div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Ukuran Penuh" id="full">
                             <span class="glyphicon glyphicon-resize-full"></span>
                         </div></a>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Refresh" id="refresh" onclick="window.location.reload();">
                             <span class="glyphicon glyphicon-refresh"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Notifikasi" id="bell">
                             <div class="glyphicon glyphicon-bell"></div>
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
        $gl_tampil2         = mysql_query("select * from gl_distributor");
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
                                            Pembelian ke Supplier
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
                                            Penjualan ke Konsumen
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
                                            <span class="glyphicon glyphicon-home"></span>
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
                            <td><a href="config_transaksi_buku.php?id_buku=<?php echo $gl_fetch['id_buku']; ?>"><button type="button" class="btn" id="green"><span class="glyphicon glyphicon-plus"></span></button></a></td>
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