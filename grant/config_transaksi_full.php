<?php
ob_start();
include"../kabel.php";
include"config_acc_sadmn.php";
$gl_text = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$gl_panjang = strlen($gl_text);
$gl_hasil =0;
for($gl_i =1;$gl_i<=7;$gl_i++){
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
        header("location:config_supplier_full.php");
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
    $gl_sql_cari = mysql_query("select * from gl_buku where  judul like '%$gl_keyword%' order by judul asc limit $gl_posisi, $gl_limit");
}else{
    $gl_sql_cari = mysql_query("select * from gl_buku order by judul asc limit $gl_posisi, $gl_limit");
}
$gl_sql_cord = mysql_query("select * from gl_buku  order by judul asc limit $gl_posisi, $gl_limit");

    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/def.css" rel="stylesheet">
        <link href="pic/mimin/nicklahara.png" rel="icon">
        <title>Graylabs - Data Transaksi</title>
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
        <div class="full">
            <div class="container-fluid">
                <div class="rows">
                    <div class="col-md-12" id="content-full">
                        <div class="rows">
                            <div class="col-md-12">
                                <div class="rows">
                                    <div class="col-md-4" id="pad-top2">
                                        <div class="rows">
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom" id="tambah" title="Data Buku" onclick="location.href='config_buku.php'">
                             <span class="glyphicon glyphicon-book"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Print Data" id="print" onclick="window.print();">
                             <span class="glyphicon glyphicon-print"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Refresh" id="refresh" onclick="window.location.reload();">
                             <span class="glyphicon glyphicon-refresh"></span>
                         </div>
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
                    </div>
                                    </div>
                                    <div class="col-md-4" id="search">
                                        <form target="_self" enctype="multipart/form-data" name="cari" method="get">
                                    <input type="search" name="sch_cari" id="sch_cari" placeholder="Search" class="col-md-10">
                                    <button type="submit" class="btn"><span class="glyphicon glyphicon-search"></span></button>
                                </form>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="config_transaksi.php"><button class="btn btn-primary tip" data-toggle="tooltip" data-placement="bottom" title="Normal" id="tutup-full"><span class="glyphicon glyphicon-resize-small"></span></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rows">
                    <div class="col-md-12" id="content-full">
                       <table class="table table-striped">
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