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


if(isset($_POST['id_dis'])){
    $gl_id_dis = strip_tags(trim($_POST['id_dis']));
    $gl_edt_kode = strip_tags(trim($_POST['gl_kode']));
    $gl_edt_nama = strip_tags(trim($_POST['gl_nama']));
    $gl_edt_alamat = strip_tags(trim($_POST['gl_alamat']));
    $gl_edt_telp = strip_tags(trim($_POST['gl_telp']));
    $gl_edt_foto    = $_FILES['gl_foto']['name']?$_FILES['gl_foto']['name']:"kosong";
    $gl_edt_type    = pathinfo($gl_edt_foto, PATHINFO_EXTENSION);
    $gl_edt_folder  = "supplier";
    $gl_foto_new     = $gl_edt_folder."_".$gl_hasil.".".$gl_edt_type;
    $gl_sql_cek           = mysql_query("select * from gl_distributor where id_distributor='$gl_id_dis'");
    $gl_jml_cek = mysql_num_rows($gl_sql_cek);
    if($gl_jml_cek <=0){
        ?>
<script>alert('Distributor <?php echo $gl_edt_nama; ?> tidak ditemukan ! Periksa kembali');history.back();</script>
<?php
    }else{
        if(!empty($_FILES['gl_foto']['name'])){
        $gl_sql_insert = mysql_query("update gl_distributor set nama_distributor='$gl_edt_nama', almat='$gl_edt_alamat', telp='$gl_edt_telp',gambar_distributor='$gl_foto_new' where id_distributor='$gl_id_dis'");
        if($gl_sql_insert  && isset($gl_edt_foto)){
            move_uploaded_file($_FILES['gl_foto']['tmp_name'],"pic/supplier/".$gl_foto_new); 
            unlink("pic/supplier/".$gl_fetch_user['gambar_distributor']);
        }
        }else{
             $gl_sql_insert = mysql_query("update gl_distributor set nama_distributor='$gl_edt_nama', almat='$gl_edt_alamat', telp='$gl_edt_telp' where id_distributor='$gl_id_dis'");
        }
            header("location:config_edt_supplier.php?id_edt_distributor=$gl_id_dis");
    }
}

if(isset($_GET['id_edt_distributor'])){
    $gl_get_id = $_GET['id_edt_distributor'];
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
        <title>Graylabs - Edit Supplier</title>
        <script>
            function cekfile(){
                var filein = document.getElementById('gl_foto');
                var info = filein.files[0];
                var ukuran = info.size;
                var mbsize = Math.round(ukuran/1048576);
                var kbsize = Math.round(ukuran/1024);
                if(ukuran > 2097152){
                    document.getElementById('msgfile').style.color="red";
                    document.getElementById('msgfile').innerHTML="Ukuran Foto Terlalu Besar dari yang ditentuakan ! Size foto : "+mbsize+ " Mb";
                    document.getElementById('btn-dis').style.display="block";
                    document.getElementById('btn-sub').style.display="none";
                }else{
                    document.getElementById('msgfile').style.color="blue";
                    document.getElementById('msgfile').innerHTML="Foto diterima ! Size foto : "+kbsize+ " Kb";
                    document.getElementById('btn-dis').style.display="none";
                    document.getElementById('btn-sub').style.display="block";
                }
            }
            function cek6(){
                var filein6 = document.getElementById('gl_password').value;
                if(filein6.length > 8){
                    document.getElementById('msgpass').style.color="green";
                    document.getElementById('msgpass').innerHTML="Password diterima !";
                    document.getElementById('btn-sub').style.display="block";
                    document.getElementById('btn-dis').style.display="none";
                }else{
                          document.getElementById('msgpass').style.color="red";
                    document.getElementById('msgpass').innerHTML="Password harus 8 karakter atau lebih !";      
                    document.getElementById('btn-dis').style.display="block";
                    document.getElementById('btn-sub').style.display="none";
                }
            }
        </script>
    </head>
    <body>
        <div class="profile" id="pro-user">
            <div class="container" id="pro-fil">
                <div class="rows">
                    <div class="col-md-12">
                        <button class="btn btn-danger tip" data-toggle="tooltip" data-placement="bottom" title="Cancel" onclick="location.href='config_supplier.php';" id="tutup-profil"><span class="glyphicon glyphicon-remove"></span></button>
                    </div>
                </div>
                <div class="rows">
                    <div class="col-md-5">
                        <img src="pic/supplier/<?php echo $gl_fetch_user['gambar_distributor']; ?>" class="img-responsive" id="img-pro-fil">
                    </div>
                    <form target="_self" name="update" id="update" enctype="multipart/form-data" method="post">
                        <input type="hidden" name="id_dis" id="id_dis" value="<?php echo $gl_fetch_user['id_distributor']; ?>">
                    <div class="col-md-7" id="desc-profil">
                        <div class="rows">
                            <div class="col-md-12" id="pad-top">
                        <div class="col-md-1">
                            <span class="glyphicon glyphicon-barcode"></span>
                        </div>
                        <div class="col-md-3" align="right">
                            Kode &nbsp; :
                        </div>
                        <div class="col-md-8" >
                             <input type="text" name="gl_kode" id="gl_kode" value="<?php echo $gl_fetch_user['kode_distributor']; ?>" placeholder="Kode Distributor" readonly class="col-md-12">
                        </div>
                                </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="pad-top">
                        <div class="col-md-1"> 
                            <span class="glyphicon glyphicon-briefcase"></span>
                        </div>
                        <div class="col-md-3" align="right">
                            Nama &nbsp; :
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="gl_nama" id="gl_nama" value="<?php echo $gl_fetch_user['nama_distributor']; ?>" placeholder="Nama Supplier" class="col-md-12">
                        </div>
                                </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="pad-top">
                        <div class="col-md-1">
                            <span class="glyphicon glyphicon-map-marker"></span>
                        </div>
                        <div class="col-md-3" align="right">
                            Alamat &nbsp; :
                        </div>
                        <div class="col-md-8">
                             <input type="text" name="gl_alamat" id="gl_alamat" value="<?php echo $gl_fetch_user['almat']; ?>" placeholder="Alamat Supplier" class="col-md-11">
                        </div>
                                </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="pad-top">
                        <div class="col-md-1">
                            <span class="glyphicon glyphicon-phone-alt"></span>
                        </div>
                        <div class="col-md-3" align="right">
                            Telepon &nbsp; :
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="gl_telp" id="gl_telp" value="<?php echo $gl_fetch_user['telp']; ?>" placeholder="Telepon" class="col-md-12">
                            </div>
                        </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="pad-top">
                        <div class="col-md-1">
                            <span class="glyphicon glyphicon-picture"></span>
                        </div>
                        <div class="col-md-3" align="right">
                            Foto :
                        </div>
                        <div class="col-md-8">
                            <input type="file" name="gl_foto" id="gl_foto" onblur="cekfile()" onchange="cekfile()" onfocus="cekfile()">
                        </div>
                                </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12">
                                <div id="msgfile"></div>
                            </div>
                        </div>
                        <div class="rows">
                            <div class="col-md-12" id="space2">
                                <button type="submit" class="btn btn-primary col-md-12 tip" data-toggle="tooltip" data-placement="bottom" id="btn-sub" title="Simpan Perubahan"><span class="glyphicon glyphicon-edit"></span> &nbsp;Simpan Perubahan</button>
                                <button type="submit" class="btn btn-primary col-md-12 tip" data-toggle="tooltip" id="btn-dis" data-placement="bottom" title="Simpan Perubahan" style="display:none;" disabled><span class="glyphicon glyphicon-edit"></span> &nbsp;Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                        </form>
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