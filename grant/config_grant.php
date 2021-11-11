<?php
ob_start();
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
    $gl_sql_cari = mysql_query("select * from gl_buku where stok <=5 order by judul asc limit $gl_posisi, $gl_limit");
}else{
    $gl_sql_cari = mysql_query("select * from gl_buku where stok <=5  order by judul asc limit $gl_posisi, $gl_limit");
}
$gl_sql_cord = mysql_query("select * from gl_buku where stok<=5  order by judul asc limit $gl_posisi, $gl_limit");


?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/def.css" rel="stylesheet">
        <script src="js/highcharts.js" type="text/javascript"></script>
        <script src="js/exporting.js" type="text/javascript"></script>
        <script src="js/jquery-2.1.4.js" type="text/javascript"></script>
        <link href="pic/mimin/nicklahara.png" rel="icon">
        <title>Graylabs - Dashboard</title>
        
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
        <script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart', 
                type: 'line',  
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Statistik Penjualan',
                x: -20 
            },
            subtitle: {
                text: 'Graylabs.com',
                x: -20
            },
            xAxis: { 
                categories: ['2006', '2007', '2008','2009','2010','2011']
            },
            yAxis: {
                title: {  
                    text: ''
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080' 
                }]
            },
            tooltip: { 
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
                        this.x +': '+ this.y ;
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            series: [{  
                name: 'Show / Hide',  
                data: [1660, 1946,2271,2590,3004,9550]
            }]
        });
    });
    
});
		</script>
    </head>
    <body>   
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
                    <div class="col-md-10" id="drop">
                        <div id="tglsec" class="date"></div>
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
        <?php
        if($_SESSION['akses']=="superadmin" or $_SESSION['akses']=="Manager" or $_SESSION['akses']=="Admin"){
            ?>
        <div class="container-fluid" style="margin-bottom:20px;">
            <div class="col-md-12" id="dis-grant">
            <div class="rows">
               <div class="col-md-7" id="sub-dis-grant" >
                   <div class="rows">
                       <div class="col-md-12" style="padding:0;">
                           <div class="rows">
                               <div class="col-md-12" id="top-grant">
                                Statistik
                               </div>
                           </div>
                           <div class="rows">
                               <div class="col-md-12">
                                   <div id="chart" style="min-width: 230px; height: 230px; "></div>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
               <div class="col-md-5" id="sub-dis-grant" >
                   <div class="rows">
                       <div class="col-md-12" style="padding:0;">
                           <div class="rows">
                               <div class="col-md-12" id="top-grant">
                                Shortcut Menu
                               </div>
                           </div>
                           <div class="rows" >
                            <a href="config_grant.php"><div class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Home" id="grant-menu1">
                                   <span class="glyphicon glyphicon-home"></span>
                                </div></a>
                            <a href="config_buku.php"><div class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Data Buku" id="grant-menu2">
                                   <span class="glyphicon glyphicon-book"></span>
                                </div></a>
                               <?php
            if($_SESSION['akses']=="Admin"){
            ?>
                            <a href="config_kasir.php"><div hidden="hidden" class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Data Pegawai" id="grant-menu3">
                                   <span class="glyphicon glyphicon-user"></span>
                                </div></a>
                               <?php
            }else{
                ?>
                               <a href="config_kasir.php"><div class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Data Pegawai" id="grant-menu3">
                                   <span class="glyphicon glyphicon-user"></span>
                                </div></a>
                               <?php
            }
                ?>
                            <a href="config_supplier.php"><div class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Data Supplier" id="grant-menu4">
                                   <span class="glyphicon glyphicon-th-large"></span>
                               </div></a>
                            <a href="config_transaksi.php"><div class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Transaksi" id="grant-menu5">
                                   <span class="glyphicon glyphicon-th-list"></span>
                                </div></a>
                            <a href="config_laporan.php"><div class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Laporan" id="grant-menu6">
                                   <span class="glyphicon glyphicon-list-alt"></span>
                                </div></a>
                               <a href="keluar.php"><div class="col-md-3 tip" data-target="tooltip" data-placement="bottom" title="Logout" id="grant-menu7">
                                   <span class="glyphicon glyphicon-off"></span>
                                </div></a>
                           </div>
                       </div>
                   </div>
                </div>
            </div> 
            </div>
            <div class="col-md-12">
            <div class="rows">
                <div class="col-md-4">
                    <div class="col-md-12" id="white" style="box-shadow:0px 0px 0px 0px;border-radius:0; border-top:solid 4px #00A65A;">
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur1">
                            Daftar Penerbit
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur2">
                            <?php
            $gl_sql_sup = mysql_query("select * from gl_distributor");
            while($gl_fetch_sup = mysql_fetch_array($gl_sql_sup)){
            ?>
                            <div class="col-md-3" style="text-align:center;">
                                <img src="pic/supplier/<?php echo $gl_fetch_sup['gambar_distributor'] ?>" class="img-sup-grant tip" data-target="tooltip" data-placement="bottom" title="<?php echo $gl_fetch_sup['nama_distributor']; ?>"><span class="small"><?php echo substr($gl_fetch_sup['nama_distributor'],0,8)."..."; ?></span>
                            </div>
                            <?php
            }
                ?><br>
                            
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur3">
                            <a href="config_supplier.php"><button class="btn-green" type="button">Lihat Semua</button></a>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-md-4" >
                    <div class="col-md-12" id="white" style="box-shadow:0px 0px 0px 0px;border-radius:0;border-top:solid 4px #F39C12;">
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur1">
                            Buku Terlaris
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur2">
                            <?php
            $gl_sql_book = mysql_query("select * from gl_buku order by jmljual asc limit 10");
            $gl_no=1;
            while($gl_fetch_book = mysql_fetch_array($gl_sql_book)){
                if($gl_no %2==0){
                    $gl_color ="#f8f8f8";
                }else{
                    $gl_color="white";
                }
 ?>
                            <div class="rows">
                                <div class="col-md-12" style="padding-top:90px;padding:0;border-bottom:solid 1px lightgray;padding-bottom:5px;background:<?php echo $gl_color; ?>">
                                <div class="col-md-3">
                                    <img src="../pic/book/<?php echo $gl_fetch_book['gambar_buku'];  ?>" class="img-grant-book">
                                </div>
                                <div class="col-md-9">
                                    <span class="middle"><?php echo $gl_fetch_book['judul']; ?><br>Terjual <b><?php echo $gl_fetch_book['jmljual']; ?></b></span>
                                    </div>
                            </div>
                                </div>
                            <?php
                $gl_no++;
            }
                ?>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur3">
                            <a href="config_buku.php"><button class="btn-kuning" type="button">Data Buku</button></a>
                        </div>
                    </div>
                        </div>
                </div>
                <div class="col-md-4" >
                    <div class="col-md-12" id="white" style="box-shadow:0px 0px 0px 0px;border-radius:0;border-top:solid 4px #DD4B39;">
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur1">
                            Penjualan Hari Ini
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur2">
                            <?php
            $gl_sql_jual = mysql_query("select * from gl_detailjual join gl_buku on(gl_detailjual.id_buku=gl_buku.id_buku) order by id_penjualan desc");
            $gl_num = 1;
            while ($gl_fetch_jual=mysql_fetch_array($gl_sql_jual)){
                if($gl_num %2==0){
                    $col = "#f8f8f8";
                }else{
                    $col = "white";
                }
            ?>
                            <div class="rows">
                                <div class="col-md-12" style="background:<?php echo $col; ?>;border-bottom:solid 1px lightgray;">
                                    <span class="small">Judul Buku : <?php echo $gl_fetch_jual['judul']; ?> <br> Terjual : <?php echo $gl_fetch_jual['jml_beli']; ?> | Rp.<?php echo $gl_fetch_jual['total_peritem']; ?></span>
                                </div>
                            </div>
                            <?php
                $gl_num++;
            }
                ?>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-12" id="sub-fitur3">
                            <a href="config_transaksi.php"><button class="btn-red" type="button">Lihat Semua</button></a>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>
        <?php
        }else{
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
                         <a href="config_buku.php"><div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom" id="tambah" title="Data Buku" >
                             <span class="glyphicon glyphicon-book"></span>
                         </div></a>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Print Data" id="print" onclick="window.print();" >
                             <span class="glyphicon glyphicon-print"></span>
                         </div>
                         <a href="config_transaksi_full.php"><div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"   title="Ukuran Penuh" id="full">
                             <span class="glyphicon glyphicon-resize-full"></span>
                         </div></a>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Refresh" id="refresh" onclick="window.location.reload();" >
                             <span class="glyphicon glyphicon-refresh"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Notifikasi" id="bell" >
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
												<button data-toggle="dropdown" class="btn btn-purple dropdown-toggle" style="height:32px;">
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
                                    <div class="col-md-3" id="blue-trans" ><span class="glyphicon glyphicon-plus" id="plus" style="margin-top:30px;"></span></div>
                                    <div class="col-md-9" id="white-trans">
                                        <div class="col-md-12" id="white-top">
                                            Pasok Buku
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
                                    <div class="col-md-3" id="red-trans" style="height:80px;"><span class="glyphicon glyphicon-plus" id="plus" style="margin-top:30px;"></span></div>
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
                                    <div class="col-md-3" id="green-trans" style="height:80px;"><span class="glyphicon glyphicon-plus" id="plus" style="margin-top:30px;"></span></div>
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
                                    <div class="col-md-3" id="yellow-trans" style="height:80px;"><span class="glyphicon glyphicon-plus" id="plus" style="margin-top:30px;"></span></div>
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
        <?php
        }
            ?>
        <script src="js/jquery-2.1.4.js" type="text/javascript"></script>
        <script src="js/highcharts.js" type="text/javascript"></script>
        <script src="js/exporting.js" type="text/javascript"></script>
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