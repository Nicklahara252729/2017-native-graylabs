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
    $gl_sql_cari = mysql_query("select * from gl_buku where judul like '%$gl_keyword%' or noisbn like '%$gl_keyword%' or penulis like '%$gl_keyword%' or penerbit like '%$gl_keyword%' or tahun like '%$gl_keyword%' order by judul asc limit $gl_posisi, $gl_limit");
    $gl_sql_full = mysql_query("select * from gl_buku where judul like '%$gl_keyword%' or noisbn like '%$gl_keyword%' or penulis like '%$gl_keyword%' or penerbit like '%$gl_keyword%' or tahun like '%$gl_keyword%' order by judul asc limit $gl_posisi, $gl_limit");
}else{
    $gl_sql_cari = mysql_query("select * from gl_buku order by judul asc limit $gl_posisi, $gl_limit");
    $gl_sql_full = mysql_query("select * from gl_buku order by judul asc limit $gl_posisi, $gl_limit");
}
$gl_sql_cord = mysql_query("select * from gl_buku");


if(isset($_GET['id_edt_buku'])){
    $gl_get_id = $_GET['id_edt_buku'];
    $gl_sql_buku = mysql_query("select * from gl_buku where id_buku='$gl_get_id'");
    $gl_fetching = mysql_fetch_array($gl_sql_buku);
}

if(isset($_POST['gl_judul'])){
    $gl_idbuku = strip_tags(trim($_POST['gl_idbuku']));
    $gl_judul = strip_tags(trim($_POST['gl_judul']));
    $gl_isbn = strip_tags(trim($_POST['gl_isbn']));
    $gl_penulis = strip_tags(trim($_POST['gl_penulis']));
    $gl_penerbit = strip_tags(trim($_POST['gl_penerbit']));
    $gl_tahun = strip_tags(trim($_POST['gl_tahun']));
    $gl_harga_pokok = strip_tags(trim($_POST['gl_harga_pokok']));
    $gl_harga_jual = strip_tags(trim($_POST['gl_harga_jual']));
    $gl_ppn = strip_tags(trim($_POST['gl_ppn']));
    $gl_diskon = strip_tags(trim($_POST['gl_diskon']));
    $gl_kategori = strip_tags(trim($_POST['gl_kategori']));
    $gl_halaman = strip_tags(trim($_POST['gl_halaman']));
    $gl_sinopsis = strip_tags(trim($_POST['gl_sinopsis']));
    $gl_cover = $_FILES['gl_cover']['name']?$_FILES['gl_cover']['name']:'gl_default.jpg';
    $gl_edt_type    = pathinfo($gl_cover, PATHINFO_EXTENSION);
    $gl_edt_folder  = "book";
    $gl_foto_new     = $gl_edt_folder."_".$gl_hasil.".".$gl_edt_type;
    $gl_sql_cek           = mysql_query("select * from gl_buku where id_buku='$gl_idbuku'");
    $gl_jml_cek = mysql_num_rows($gl_sql_cek);
    $gl_fetch_buku = mysql_fetch_array($gl_sql_cek);
    if($gl_jml_cek <=0){
        ?>
<script>alert('Buku <?php echo $gl_edt_nama; ?> tidak ditemukan ! Periksa kembali');history.back();</script>
<?php
    }else{
        if(!empty($_FILES['gl_cover']['name'])){
        $gl_sql_insert = mysql_query("update gl_buku set judul='$gl_judul',noisbn='$gl_isbn',penulis='$gl_penulis',penerbit='$gl_penerbit', tahun='$gl_tahun',harga_pokok='$gl_harga_pokok',harga_jual='$gl_harga_jual',ppn='$gl_ppn',diskon='$gl_diskon',gambar_buku='$gl_foto_new',kategori='$gl_kategori',jml_hal='$gl_halaman',sinopsis='$gl_sinopsis' where id_buku='$gl_idbuku'");
        if($gl_sql_insert  && isset($gl_cover)){
            move_uploaded_file($_FILES['gl_cover']['tmp_name'],"../pic/book/".$gl_foto_new); 
            unlink("../pic/book/".$gl_fetch_buku['gambar_buku']);
        }
        }else{
             $gl_sql_insert = mysql_query("update gl_buku set judul='$gl_judul',noisbn='$gl_isbn',penulis='$gl_penulis',penerbit='$gl_penerbit', tahun='$gl_tahun',harga_pokok='$gl_harga_pokok',harga_jual='$gl_harga_jual',ppn='$gl_ppn',diskon='$gl_diskon',kategori='$gl_kategori',jml_hal='$gl_halaman',sinopsis='$gl_sinopsis' where id_buku='$gl_idbuku'");
        }
            header("location:config_buku.php");
    
    }
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
        <title>Graylabs - Data Buku</title>
       
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
        <style>
            td{
                height: 28px;
                padding-left: 10px;
            }
        </style>
    </head>
    <body>
        <div class="full" style="display:block;">
            <div class="container-fluid">
                <div class="rows">
                    <div class="col-md-12" id="content-full" style="padding:0;height:570px;background:#f8f8f8;">
                        <div class="rows">
                            <div class="col-md-12">
                                <a href="config_buku.php"><button class="btn btn-danger tip" data-toggle="tooltip" data-placement="bottom" title="Back" id="tutup-full"><span class="glyphicon glyphicon-remove"></span></button></a>
                            </div>
                        </div>
                        <form target="_self" method="post" enctype="multipart/form-data" name="edt-buku">
                    <div class="rows">
                    <div class="col-md-3" style="height:100%;">
                                <div class="rows">
                                    <img src="../pic/book/<?php echo $gl_fetching['gambar_buku']; ?>" class="thumbnail " style="width:100%;height:440px;margin-top:10px;border:solid 1px lightgray;">
                                </div>
                                
                        </div>
                    
                    <div class="col-md-3">
                                    <p class="middle">
                                        <table style="width:100%;box-shadow:0px 0px 0px 0px; border:solid 1px lightgray;">
                                            <tr>
                                                <td>Judul</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-12" type="text" name="gl_judul"    
                                                    value="<?php echo $gl_fetching['judul']; ?>" placeholder="Judul" required>
                                                <input class="col-md-12" type="hidden" name="gl_idbuku"    
                                                    value="<?php echo $gl_fetching['id_buku']; ?>" placeholder="Judul" required></td>
                                            </tr>
                                            <tr>
                                                <td>ISBN</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-12" type="text" name="gl_isbn" value="<?php echo $gl_fetching['noisbn']; ?>" placeholder="ISBN" required></td>
                                            </tr>
                                            <tr>
                                                <td>Penulis</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-12" type="text" name="gl_penulis" value="<?php echo $gl_fetching['penulis']; ?>" placeholder="Penulis" required></td>
                                            </tr>
                                            <tr>
                                                <td>Penerbit</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-12" type="text" name="gl_penerbit" value="<?php echo $gl_fetching['penerbit']; ?>" required placeholder="Penerbit"></td>
                                            </tr>                                   
                                            <tr>
                                                <td>Tahun</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-12" type="text" name="gl_tahun" value="<?php echo $gl_fetching['tahun']; ?>" placeholder="Tahun" required></td>
                                            </tr>
                                            <tr>
                                                <td>Stok</td>
                                                <td>:</td>
                                                <td>
                                                <input type="text" disabled value="<?php echo $gl_fetching['stok']; ?>" required ></td>
                                            </tr>
                                            <tr>
                                                <td>Pembelian</td>
                                                <td>:</td>
                                                <td>
                                                <input type="text" value="<?php echo $gl_fetching['jmlbeli']; ?>" disabled></td>
                                            </tr>
                                            <tr>
                                                <td>Penjualan</td>
                                                <td>:</td>
                                                <td>
                                                <input type="text" value="<?php echo $gl_fetching['jmljual']; ?>" disabled></td>
                                            </tr>                                   
                                            <tr>
                                                <td>Harga Pokok</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-11" type="number" name="gl_harga_pokok" value="<?php echo $gl_fetching['harga_pokok']; ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td>Harga Jual</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-11" type="number" name="gl_harga_jual" value="<?php echo $gl_fetching['harga_jual']; ?>" required></td>
                                            </tr>
                                            <tr>
                                                <td>PPN</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-11" type="number" name="gl_ppn" value="<?php echo $gl_fetching['ppn']; ?>" required> %</td>
                                            </tr>
                                            <tr>
                                                <td>Diskon</td>
                                                <td>:</td>
                                                <td>
                                                <input class="col-md-11" type="number" name="gl_diskon" value="<?php echo $gl_fetching['diskon']; ?>" required> %</td>
                                            </tr>
                                            <tr>
                                                <td>Kategori</td>
                                                <td>:</td>
                                                <td>
                                                <input type="text" name="gl_kategori" required placeholder="Kategori" value="<?php echo $gl_fetching['kategori']; ?>" class="col-md-11">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Halaman</td>
                                                <td>:</td>
                                                <td>
                                                <input type="number" name="gl_halaman" class="col-md-11" value="<?php echo $gl_fetching['jml_hal']; ?>" required min="1"></td>
                                            </tr>
                                            <tr>
                                                <td>Cover</td>
                                                <td>:</td>
                                                <td>
                                                <input type="file" name="gl_cover" class="col-md-11" ></td>
                                            </tr>
                                    </table>
                                    </p>

                        </div>
                    <div class="col-md-6">
                                <div class="rows">
                                    <div class="col-md-12 middle" style="margin-top:10px;background:white;border:solid 1px lightgray;height:440px;">
                                <textarea style="height:99%;width:620px;" name="gl_sinopsis" placeholder="Sinopsisi"><?php echo $gl_fetching['sinopsis']; ?> </textarea>
                                    </div>
                                </div>
                    </div>  
                        </div>
                        <div class="rows">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="float:right;">
                                    <span class="glyphicon glyphicon-download"></span> &nbsp; Simpan Perubahan
                                </button>
                            </div>
                        </div>
                        </form>
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
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Refresh" id="refresh" onclick="window.location.reload()">
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
        $gl_tampil2         = mysql_query("select * from gl_buku");
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
                            <th>Cover</th>
                            <th>Judul</th>
                            <th>ISBN</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
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
                            <td><?php echo $gl_fetch['penerbit']; ?></td>
                            <td><?php echo $gl_fetch['tahun']; ?></td>
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
														<a href="config_det_buku.php?id_det_buku=<?php echo $gl_fetch['id_buku']; ?>">
															<i class="glyphicon glyphicon-folder-open"></i> &nbsp; Detail
														</a>
													</li>
													<li>
														<a href="config_edt_buku.php?id_edt_buku=<?php echo $gl_fetch['id_buku']; ?>">
															<i class="glyphicon glyphicon-pencil"></i> &nbsp; Edit
														</a>
													</li>
													<li>
														<a href="config_buku.php?id_hps_buku=<?php echo $gl_fetch['id_buku']; ?>" onClick="return confirm('Apakah anda yakin ingin menghapus data ini ?');"> 
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