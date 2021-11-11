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
    $gl_sql_cari = mysql_query("select * from gl_distributor where kode_distributor like '%$gl_keyword%' or nama_distributor like '%$gl_keyword%' order by kode_distributor asc limit $gl_posisi, $gl_limit");
}else{
    $gl_sql_cari = mysql_query("select * from gl_distributor order by kode_distributor asc limit $gl_posisi, $gl_limit");
}
$gl_sql_cord = mysql_query("select * from gl_distributor");

if(isset($_GET['id_det_supplier'])){
    $gl_get_id = $_GET['id_det_supplier'];
    $gl_sql_user = mysql_query("select * from gl_distributor where id_distributor='$gl_get_id'");
    $gl_fetch_user = mysql_fetch_array($gl_sql_user);
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
        <title>Graylabs - Detail Supplier</title>
    </head>
    <body>
        <div class="profile" id="pro-user">
            <div class="container" id="pro-fil">
                <div class="rows">
                    <div class="col-md-12">
                        <button class="btn btn-danger tip" data-toggle="tooltip" data-placement="bottom" title="Close" onclick="history.back();" id="tutup-profil"><span class="glyphicon glyphicon-remove"></span></button>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-md-5">
                        <img src="pic/supplier/<?php echo $gl_fetch_user['gambar_distributor']; ?>" class="img-responsive" id="img-pro-fil">
                    </div>
                    <div class="col-md-7" id="desc-profil">
                        <div class="rows">
                        <div class="col-md-1" id="pad-top">
                            <span class="glyphicon glyphicon-barcode"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Kode
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $gl_fetch_user['kode_distributor']; ?>
                        </div>
                        </div>
                        <div class="rows">
                        <div class="col-md-1" id="pad-top"> 
                            <span class="glyphicon glyphicon-briefcase"></span>
                        </div>
                        <div class="col-md-3" id="pad-top">
                            Nama
                        </div>
                        <div class="col-md-8" id="pad-top">
                            : &nbsp; <?php echo $gl_fetch_user['nama_distributor']; ?>
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
                            : &nbsp; <?php echo $gl_fetch_user['almat']; ?>
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
                            : &nbsp; <?php echo $gl_fetch_user['telp']; ?>
                        </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="space2">
                                <a href="config_edt_supplier.php?id_edt_distributor=<?php echo $gl_fetch_user['id_distributor']; ?>"><button class="btn btn-warning col-md-12 tip" data-toggle="tooltip" data-placement="bottom" title="Ubah data"><span class="glyphicon glyphicon-edit"></span> &nbsp; Ubah</button></a>
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
                        <img src="pic/mimin/<?php echo $_SESSION['gambar_kasir']; ?>" class="img-toggle tip" id="img-toggle" onclick="slide();" data-toggle="tooltip" data-placement="bottom" title="<?php echo $_SESSION['nama']; ?>">
                    </div>
                </div>       
            </div>
        </div>
    </nav>
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
                <div class="col-md-12" id="nav" style="background: #03294A;
    color: white;
    cursor: pointer;
    border-left: solid 5px yellow;">
                        <div class="col-md-2">
                            <span class="glyphicon glyphicon-book"></span>
                        </div>
                        <div class="col-md-10">
                            DATA BUKU
                        </div>
                    </div>
                </div></a>
            <a href="config_kasir.php"><div class="rows">
                <div class="col-md-12" id="nav">
                        <div class="col-md-2">
                            <span class="glyphicon glyphicon-user"></span>
                        </div>
                        <div class="col-md-10">
                            DATA KASIR
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
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom" id="tambah" title="Tambah Data">
                             <span class="glyphicon glyphicon-plus"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Print Data" id="print" onclick="window.print();">
                             <span class="glyphicon glyphicon-print"></span>
                         </div>
                         <a href="config_buku_full.php"><div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Ukuran Penuh" id="full">
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
													<i class="fa fa-wrench"></i>
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
                    <table class="table">
                        <tr>
                            <th>NO.</th>
                            <th>Logo</th>
                            <th>Kode Distributor</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
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
                            <td><img src="pic/supplier/<?php echo $gl_fetch['gambar_distributor']; ?>" class="img-cover"></td>
                            <td><?php echo $gl_fetch['kode_distributor']; ?></td>
                            <td><?php echo $gl_fetch['nama_distributor']; ?></td>
                            <td><?php echo $gl_fetch['almat']; ?></td>
                            <td><?php echo $gl_fetch['telp']; ?></td>
                            <td><div class="btn-group">
												<button type="button" class="btn btn-purple">
													<i class="fa fa-wrench"></i>
													Option
												</button>
												<button data-toggle="dropdown" class="btn btn-purple dropdown-toggle">
													<span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
                                                    <li>
														<a href="config_det_supplier.php?id_det_supplier=<?php echo $gl_fetch['id_distributor']; ?>">
															<i class="glyphicon glyphicon-folder-open"></i> &nbsp; Detail
														</a>
													</li>
													<li>
														<a href="config_edt_supplier.php?id_edt_distributor=<?php echo $gl_fetch['id_distributor']; ?>">
															<i class="glyphicon glyphicon-pencil"></i> &nbsp; Edit
														</a>
													</li>
													<li>
														<a href="config_supplier.php?id_hps_dis=<?php echo $gl_fetch['id_distributor']; ?>" onClick="return confirm('Apakah anda yakin ingin menghapus data ini ?');"> 
															<i class="glyphicon glyphicon-trash"></i> &nbsp; Delete
														</a>
													</li>
													
												</ul>
											</div></td>
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