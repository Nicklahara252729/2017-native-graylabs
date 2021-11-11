<?php
ob_start();
if((empty($_GET['destroy'])==FALSE)){
    session_destroy();
}
include"../kabel.php";
include"config_acc_sadmn.php";
$gl_text = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
$gl_panjang = strlen($gl_text);
$gl_hasil =0;
for($gl_i =1;$gl_i<=8;$gl_i++){
$gl_hasil = trim($gl_hasil).substr($gl_text,mt_rand(0,$gl_panjang),1);
}

$gl_sql_count = mysql_query("select count(id_pasok) as akhir from gl_pasok");
$gl_sql_row= mysql_fetch_array($gl_sql_count, MYSQL_ASSOC);
$gl_num = $gl_sql_row['akhir'];
$gl_number = $gl_num+1; 
switch(strlen($gl_number)){
    case 1 : $gl_kode = "TI0000".$gl_number; break;
    case 2 : $gl_kode = "TI000".$gl_number; break;
    case 3 : $gl_kode = "TI00".$gl_number; break;
    case 4 : $gl_kode = "TI0".$gl_number; break;
        default :$gl_kode = $gl_number;
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
    $gl_sql_cari = mysql_query("select * from  gl_buku  order by judul asc limit $gl_posisi, $gl_limit");
}else{
    $gl_sql_cari = mysql_query("select * from  gl_buku  order by judul asc limit $gl_posisi, $gl_limit");
}
$gl_sql_cord = mysql_query("select * from  gl_buku  order by judul asc limit $gl_posisi, $gl_limit");

if(isset($_GET['gl_id_pasok'])){
    $gl_get_pasok = strip_tags(trim($_GET['gl_id_pasok']));
$gl_show_transit = mysql_query("select * from gl_transit where id_pasok='$gl_get_pasok'");    
}

if(isset($_GET['reset'])){
    unset($_SESSION['transaksi']);
    unset($_SESSION['akhir']);
    unset($_SESSION['total']);
    header("location:config_transaksi_import.php");
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
        <title>Graylabs - Pembelian Buku</title>
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
                    <div class="col-md-12" id="content-full">
                        <div class="rows">
                            <div class="col-md-12">
                                <div class="rows">
                                    <div class="col-md-4" id="pad-top2">
                                        <div class="rows">
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Refresh" id="refresh" onclick="window.location.reload()">
                             <span class="glyphicon glyphicon-refresh"></span>
                         </div>
                    </div>
                                    </div>
                                    <div class="col-md-4" id="search" align="center">
                                        <h3>PASOK BUKU</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="config_transaksi.php"><button class="btn btn-danger tip" data-toggle="tooltip" data-placement="bottom" title="Cancel" id="tutup-full"><span class="glyphicon glyphicon-remove"></span></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rows">
                    <div class="col-md-5" style="padding-bottom:20px;border:solid 1px lightgray;margin-bottom:10px;height:300px;">
                        <form target="_self" enctype="multipart/form-data" name="import" id="import" method="post">
                            <div class="col-md-12" id="pad-top">
                                <div class="col-md-1">
                                    <span class="glyphicon glyphicon-barcode"></span>
                                </div>
                                <div class="col-md-4" align="right">
                                    No.Transaksi :
                                </div>
                                <div class="col-md-7" align="right">
                                     <input type="text" name="gl_no_trans" id="gl_no_trans" value="<?php echo $gl_kode; ?>" readonly class="col-md-12">
                                </div>
                                </div>
                            <div class="col-md-12" id="pad-top">
                                <div class="col-md-1">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </div>
                                <div class="col-md-4" align="right">
                                    Tanggal :
                                </div>
                                <div class="col-md-7">
                                    <input type="text" name="gl_tanggal" id="id_tanggal" value="<?php date_default_timezone_set("Asia/Jakarta");echo date("d-m-Y H:ia"); ?>" readonly class="col-md-12">
                                </div>
                            </div>
                            
                            <div class="col-md-12" id="pad-top">
                                <div class="col-md-1">
                                    <span class="glyphicon glyphicon-book"></span>
                                </div>
                                <div class="col-md-4" align="right">
                                    Judul :
                                </div>
                                <div class="col-md-7">
                                    
                                    <select required name="gl_buku" id="gl_buku" class="col-md-12">
                                        <option disabled selected>- Pilih Buku -</option>
                                        <?php
                                    $gl_get_sup = mysql_query("select * from gl_buku");
                                    while($gl_fetch_dis = mysql_fetch_array($gl_get_sup)){
                                    ?>
                                        <option value="<?php echo $gl_fetch_dis['id_buku']; ?>"><?php echo $gl_fetch_dis['judul']; ?></option><?php
                                    }
                                        ?>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-md-12" id="pad-top">
                                <div class="col-md-1">
                                    <span class="glyphicon glyphicon-equalizer"></span>
                                </div>
                                <div class="col-md-4" align="right">
                                    Jumlah :
                                </div>
                                <div class="col-md-7">
                                     <input type="number" name="gl_jumlah" id="gl_jumlah" class="col-md-12" required placeholder="Jumlah" min="1">
                                </div>
                            </div>
                            <div class="col-md-12" id="pad-top2">
                                <button type="submit" class="col-md-12 btn btn-primary">
                                    <span class="glyphicon glyphicon-save"></span> &nbsp; Add
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-7" id="content-full">
                        <form target="_self" enctype="multipart/form-data" name="paid" id="paid" method="post">                            
                                            <?php
			$gl_awal=1;$gl_sub=0;$gl_total=0;
                @$gl_kode = strip_tags(trim($gl_kode));
                @$gl_kasir = strip_tags(trim($_POST['gl_kasir']));
				@$gl_jmlh=strip_tags(trim($_POST['gl_jumlah']));
                @$gl_id_buku = strip_tags(trim($_POST['gl_buku']));
                @$gl_no_trans = strip_tags(trim($_POST['gl_no_trans']));
                @$gl_tanggal = strip_tags(trim($_POST['gl_tanggal']));
                @$gl_tampil = mysql_fetch_array(mysql_query("select * from gl_buku join gl_distributor on(gl_buku.id_distributor=gl_distributor.id_distributor) where gl_buku.id_buku= '$gl_id_buku'"));
                @$gl_supplier = @$gl_tampil['id_distributor'];
				@$gl_harga_pokok=@$gl_tampil['harga_pokok'];
				@$gl_judul=@$gl_tampil['judul'];
			if (@$gl_id_buku!=''){
				if (empty($_SESSION["transaksi"])==TRUE){
					$_SESSION["transaksi"]=1;
				}else{
					$_SESSION["transaksi"]++;
				}
				
				@$gl_sub=$gl_harga_pokok * $gl_jmlh;
				$_SESSION["akhir"][$_SESSION["transaksi"]]=array($gl_judul,$gl_tanggal,$gl_harga_pokok,$gl_jmlh,$gl_sub,$gl_id_buku,$gl_kode,$gl_supplier);
                header("location:config_transaksi_import.php");
			}
                            ?>
                        <table class="table table-striped">
                            <tr>
                                <th>No.</th>
                                <th>Judul</th>
                                <th>Tanggal </th>
                                <th style="text-align:center;">@</th>
                                <th>Count</th>
                                <th>Total</th>
                            </tr>
            
                            <?php

				@$gl_awal = $_SESSION["transaksi"];
				
				for ($i=0;$i<=$gl_awal;$i++) {
				if (@$_SESSION["akhir"][$i][0]!=''){ ?>
                            <tr>
							<td>
                                <?php echo $i ?>
                                <input type="hidden" name="gl_x_kode" value="<?php echo @$_SESSION['akhir'][$i][6]?>">
                                <input type="hidden" name="gl_x_kasir" value="<?php echo $_SESSION['id_kasir'];?>">
                                <input type="hidden" name="gl_x_id_buku" value="<?php echo @$_SESSION['akhir'][$i][6] ?>">
                                <input type="hidden" name="gl_x_tanggal" value="<?php date_default_timezone_set("Asia/Jakarta");echo date("d-m-Y H:ia"); ?>">
                                <input type="hidden" name="gl_x_konsumen" value="<?php echo @$_SESSION['akhir'][$i][8] ?>">
                                </td>
							<td><?php echo @$_SESSION['akhir'][$i][0] ?></td>
							<td><?php echo @$_SESSION['akhir'][$i][1] ?></td>
							<td><?php echo @$_SESSION['akhir'][$i][2] ?> </td>
							<td><?php echo @$_SESSION['akhir'][$i][3] ?> </td>
							<td align="right">
                                <?php echo"Rp ".number_format(@$_SESSION['akhir'][$i][4],0,',','.'); ?>
                                <input type="hidden" name="gl_x_jml" value="<?php echo @$_SESSION['akhir'][$i][4] ?>">
                                </td>
							
					</tr>
                            <?php
                                                   }
                    $gl_total=@$_SESSION['akhir'][$i][4]+$gl_total;
                 
                }
                               
                    ?>
                            <tr>
                                <td colspan="5" align="center">Total Bayar</td>
                                <td align="right">
                                    <?php
					
					echo"Rp ".number_format(@$_SESSION['total'] = $gl_total,0,',','.');
			?>
                                    <input type="hidden" name="gl_x_total" value="<?php echo @$_SESSION['total'] = $gl_total;?>">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" align="right" id="paid">
                                    <button type="button" onclick="location.href='config_transaksi_import.php?reset'" name="reset" id="reset" class="btn btn-danger">Reset</button>
                                    <?php
if(isset($_POST['gl_x_kode'])){
        $gl_x_kode2 = strip_tags(trim($_POST['gl_x_kode']));
        $gl_x_kasir2 = strip_tags(trim($_POST['gl_x_kasir']));
        $gl_x_tanggal2 = strip_tags(trim($_POST['gl_x_tanggal']));
        $gl_x_total2 = strip_tags(trim($_POST['gl_x_total']));
    $gl_sql_det = mysql_query("insert into gl_pasok set id_pasok='$gl_x_kode2', jumlah='$gl_x_total2',tanggal='$gl_x_tanggal2',id_kasir='$_SESSION[id_kasir]'");
    $j=0;
    $gl_awal=$_SESSION["transaksi"];
	while($j <= $gl_awal){
        $gl_x_kode = @$_SESSION['akhir'][$j][6];
        $gl_x_kasir = $_SESSION['nama'];
        $gl_x_id_buku = @$_SESSION['akhir'][$j][5];
        $gl_x_tanggal =@$_SESSION['akhir'][$j][1] ;
        $gl_x_supplier = @$_SESSION['akhir'][$j][7];
        $gl_x_jml = @$_SESSION['akhir'][$j][3];
        $gl_x_totperitem = @$_SESSION['akhir'][$j][4];
        $gl_x_total = strip_tags(trim($_POST['gl_x_total']));
        if($gl_x_kode!="" and $gl_x_id_buku!="" and $gl_x_jml!=""){
			$query = mysql_query("insert into gl_detailpasok set id_pasok='$gl_x_kode',id_distributor='$gl_x_supplier', id_buku='$gl_x_id_buku',jml_beli='$gl_x_jml',total='$gl_x_totperitem'");
            $gl_sql_updt = mysql_query("update gl_buku set stok=(stok+$gl_x_jml),jmlbeli=(jmlbeli+$gl_x_jml) where id_buku='$gl_x_id_buku'");
            
        }
		$j++;
	}
    if($query && $gl_sql_updt){
            unset($_SESSION['transaksi']);
            unset($_SESSION['akhir']);
            unset($_SESSION['total']);
            }
    ?>
                                    <script>alert('Transaksi Berhasil');location.href="config_transaksi.php";</script>
                                    <?php
}
                                    ?>
                                    <button type="submit" class="btn " id="green">Pasok</button>
                                </td>
                            </tr>
                        </table>
                            </form>
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