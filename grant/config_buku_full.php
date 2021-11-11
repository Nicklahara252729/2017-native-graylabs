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

if(isset($_POST['id_buku'])){
    $gl_id_buku     = strip_tags(trim($_POST['id_buku']));
    $gl_judul_buku  = strip_tags(trim($_POST['gl_buku']));
    $gl_noisbn      = strip_tags(trim($_POST['gl_noisbn']));
    $gl_penulis     = strip_tags(trim($_POST['gl_penulis']));
    $gl_penerbit    = strip_tags(trim($_POST['gl_penerbit']));
    $gl_tahun       = strip_tags(trim($_POST['gl_tahun']));
    $gl_jmlhal        = strip_tags(trim($_POST['gl_jmlhal']));
    $gl_kategori        = strip_tags(trim($_POST['gl_kategori']));    
    $gl_sinopsis        = strip_tags(trim($_POST['gl_sinopsis']));    
    $gl_hp          = strip_tags(trim($_POST['gl_harga_pokok']));
    $gl_hj          = strip_tags(trim($_POST['gl_harga_jual']));
    $gl_ppn         = strip_tags(trim($_POST['gl_ppn']));
    $gl_diskon      = strip_tags(trim($_POST['gl_diskon']));
    $gl_gambar      = $_FILES['gl_gambar_buku']['name']?$_FILES['gl_gambar_buku']['name']:"default-buku.png";
    $gl_gambar_type    = pathinfo($gl_gambar, PATHINFO_EXTENSION);
    $gl_gambar_folder  = "book";
    $gl_gambar_new     = $gl_gambar_folder."_".$gl_hasil.".".$gl_gambar_type;
    $gl_cek_barang  = mysql_query("select * from gl_buku where noisbn='$gl_noisbn'");
    $gl_cek_jml     = mysql_num_rows($gl_cek_barang);
    if($gl_cek_jml > 0){
        ?>
<script>alert('Buku Dengan ISBN <?php echo $gl_noisbn; ?> Sudah Ada');history.back();</script>
<?php
    }else{
        $gl_simpan = mysql_query("insert into gl_buku set id_buku='$gl_id_buku', judul='$gl_judul_buku', noisbn='$gl_noisbn', penulis='$gl_penulis', id_distributor='$gl_penerbit', tahun='$gl_tahun', harga_pokok='$gl_hp', harga_jual='$gl_hj', ppn='$gl_ppn', diskon='$gl_diskon', gambar_buku='$gl_gambar_new', kategori='$gl_kategori', jml_hal='$gl_jmlhal', sinopsis='$gl_sinopsis'");
        if($gl_simpan && isset($_FILES['gl_gambar_buku']['name'])){
            move_uploaded_file($_FILES['gl_gambar_buku']['tmp_name'],"../pic/book/".$gl_gambar_new);
        }
        header("location:config_buku.php");
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
    $gl_sql_cari = mysql_query("select * from gl_buku join gl_distributor on (gl_buku.id_distributor=gl_distributor.id_distributor) where gl_buku.judul like '%$gl_keyword%' or gl_buku.noisbn like '%$gl_keyword%' or gl_buku.penulis like '%$gl_keyword%' or gl_distributor.nama_distributor like '%$gl_keyword%' or gl_buku.tahun like '%$gl_keyword%' order by gl_buku.judul asc limit $gl_posisi, $gl_limit");
    $gl_sql_full = mysql_query("select * from gl_buku join gl_distributor on (gl_buku.id_distributor=gl_distributor.id_distributor) where gl_buku.judul like '%$gl_keyword%' or gl_buku.noisbn like '%$gl_keyword%' or gl_buku.penulis like '%$gl_keyword%' or gl_distributor.nama_distributor like '%$gl_keyword%' or gl_buku.tahun like '%$gl_keyword%' order by gl_buku.judul asc limit $gl_posisi, $gl_limit");
}else{
    $gl_sql_cari = mysql_query("select * from gl_buku join gl_distributor on (gl_buku.id_distributor=gl_distributor.id_distributor)  order by gl_buku.judul asc limit $gl_posisi, $gl_limit");
    $gl_sql_full = mysql_query("select * from gl_buku join gl_distributor on (gl_buku.id_distributor=gl_distributor.id_distributor) order by gl_buku.judul asc limit $gl_posisi, $gl_limit");
}
$gl_sql_cord = mysql_query("select * from gl_buku");



if(isset($_GET['id_hps_buku'])){
    $gl_get_id = $_GET['id_hps_buku'];
    $gl_sql_get = mysql_query("select * from gl_buku where id_buku='$gl_get_id'");
    $gl_fetch_gambar = mysql_fetch_array($gl_sql_get);
    $gl_sql_hps = mysql_query("delete from gl_buku where id_buku='$gl_get_id'");
    if($gl_sql_hps){
        unlink("../pic/book/".$gl_fetch_gambar['gambar_buku']);
    }
    header("location:config_buku.php");
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
            function cekfile(){
                var filein = document.getElementById('gl_gambar_buku');
                var info = filein.files[0];
                var ukuran = info.size;
                var mbsize = Math.round(ukuran/1048576);
                var kbsize = Math.round(ukuran/1024);
                if(ukuran > 2097152){
                    document.getElementById('msgfile').style.color="red";
                    document.getElementById('msgfile').innerHTML="Ukuran Foto Terlalu Besar dari yang ditentuakan ! Size foto : "+mbsize+ " Mb";
                    document.getElementById('btn-dis').style.display="block";
                    document.getElementById('btn-sub').style.display="none";
                    document.getElementById('rm-sign11').style.display="block";
                    document.getElementById('ok-sign11').style.display="none";
                }else{
                    document.getElementById('msgfile').style.color="blue";
                    document.getElementById('msgfile').innerHTML="Foto diterima ! Size foto : "+kbsize+ " Kb";
                    document.getElementById('btn-dis').style.display="none";
                    document.getElementById('btn-sub').style.display="block";
                    document.getElementById('ok-sign11').style.display="block";
                    document.getElementById('rm-sign11').style.display="none";
                }
            }
            function cek10(){
                var filein10 = document.getElementById('gl_diskon').value;
                if(filein10.length > 0){
                    document.getElementById('ok-sign10').style.display="block";
                    document.getElementById('rm-sign10').style.display="none";
                }else{
                    document.getElementById('ok-sign10').style.display="none";
                    document.getElementById('rm-sign10').style.display="block";
                }
            }
            function cek9(){
                var filein9 = document.getElementById('gl_ppn').value;
                if(filein9.length > 0){
                    document.getElementById('ok-sign9').style.display="block";
                    document.getElementById('rm-sign9').style.display="none";
                }else{
                    document.getElementById('ok-sign9').style.display="none";
                    document.getElementById('rm-sign9').style.display="block";
                }
            }
            function cek8(){
                var filein8 = document.getElementById('gl_harga_jual').value;
                if(filein8.length > 0){
                    document.getElementById('ok-sign8').style.display="block";
                    document.getElementById('rm-sign8').style.display="none";
                }else{
                    document.getElementById('ok-sign8').style.display="none";
                    document.getElementById('rm-sign8').style.display="block";
                }
            }
            function cek7(){
                var filein7 = document.getElementById('gl_harga_pokok').value;
                if(filein7.length > 0){
                    document.getElementById('ok-sign7').style.display="block";
                    document.getElementById('rm-sign7').style.display="none";
                }else{
                    document.getElementById('ok-sign7').style.display="none";
                    document.getElementById('rm-sign7').style.display="block";
                }
            }
            function cek6(){
                var filein6 = document.getElementById('gl_stok').value;
                if(filein6.length >= 0){
                    document.getElementById('ok-sign6').style.display="block";
                    document.getElementById('rm-sign6').style.display="none";
                }else{
                    document.getElementById('ok-sign6').style.display="none";
                    document.getElementById('rm-sign6').style.display="block";
                }
            }
            function cek5(){
                var filein5 = document.getElementById('gl_tahun').value;
                if(filein5.length > 1){
                    document.getElementById('ok-sign5').style.display="block";
                    document.getElementById('rm-sign5').style.display="none";
                }else{
                    document.getElementById('ok-sign5').style.display="none";
                    document.getElementById('rm-sign5').style.display="block";
                }
            }
            function cek4(){
                var filein4 = document.getElementById('gl_penerbit').optSelected;
                if(filein4){
                    document.getElementById('ok-sign4').style.display="none";
                    document.getElementById('rm-sign4').style.display="block";
                }else{
                    document.getElementById('ok-sign4').style.display="block";
                    document.getElementById('rm-sign4').style.display="none";
                }
            }
            function cek3(){
                var filein3 = document.getElementById('gl_penulis').value;
                if(filein3.length > 1){
                    document.getElementById('ok-sign3').style.display="block";
                    document.getElementById('rm-sign3').style.display="none";
                }else{
                    document.getElementById('ok-sign3').style.display="none";
                    document.getElementById('rm-sign3').style.display="block";
                }
            }
            function cek2(){
                var filein2 = document.getElementById('gl_noisbn').value;
                if(filein2.length > 1){
                    document.getElementById('ok-sign2').style.display="block";
                    document.getElementById('rm-sign2').style.display="none";
                }else{
                    document.getElementById('ok-sign2').style.display="none";
                    document.getElementById('rm-sign2').style.display="block";
                }
            }
            function cek1(){
                var filein1 = document.getElementById('gl_buku').value;
                if(filein1.length > 1){
                    document.getElementById('ok-sign1').style.display="block";
                    document.getElementById('rm-sign1').style.display="none";
                }else{
                    document.getElementById('ok-sign1').style.display="none";
                    document.getElementById('rm-sign1').style.display="block";
                }
            }
            function cekkategori(){
                var sel = document.getElementById('gl_kategori').optSelected;
                if(sel){
                    document.getElementById('ok-sign12').style.display="block";
                    document.getElementById('rm-sign12').style.display="none";
                }else{
                    document.getElementById('ok-sign12').style.display="block";
                    document.getElementById('rm-sign12').style.display="none";
                }
            }
        </script>
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
        <div class="long">
            <div class="container-fluid" id="in-jumbo">
                <div class="container" id="login">
                <div class="col-md-12">
                    <div class="img-konsumen"></div>
                    <button class="btn btn-danger tip" data-toggle="tooltip" data-placement="bottom" title="Close" id="tutup-buku"><span class="glyphicon glyphicon-remove"></span></button>
                    </div>
                <form target="_self" enctype="multipart/form-data" name="inpt_konsumen" id="inpt_konsumen" method="post">
                    <input type="hidden" name="id_buku" id="id_buku" value="BK_<?php echo $gl_hasil; ?>">
                <div class="col-md-12" id="login-bawah">
                <div class="col-md-6" id="white1">
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-book"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="text" placeholder="Judul Buku" name="gl_buku" id="gl_buku" required class="col-md-12" onblur="cek1()" onchange="cek1()" onfocus="cek1()">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign1"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign1" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-bookmark"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="text" placeholder="NO ISBN" name="gl_noisbn" id="gl_noisbn" required class="col-md-12" onblur="cek2()" onfocus="cek2()" onchange="cek2()">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign2"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign2" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-user"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="text" placeholder="Penulis" name="gl_penulis" id="gl_penulis" required class="col-md-12" onblur="cek3()" onchange="cek3()" onfocus="cek3()">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign3"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign3" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-certificate"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <select class="col-md-12" name="gl_penerbit" required onselect="cek4()" onblur="cek4()" id="gl_penerbit">
                                <?php
                                $gl_sql_dis = mysql_query("select * from gl_distributor order by nama_distributor desc");
                                while ($gl_ambil_penerbit = mysql_fetch_array($gl_sql_dis)){
                            echo "<option value='$gl_ambil_penerbit[id_distributor]'>$gl_ambil_penerbit[nama_distributor]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign4"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign4" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="text" placeholder="Tahun Terbit" name="gl_tahun" id="gl_tahun" required class="col-md-12" onchange="cek5()" onfocus="cek5()" onblur="cek5()">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign5"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign5" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-bookmark"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="number" placeholder="Jumlah Halaman" name="gl_jmlhal" id="gl_jmlhal" min="0" required class="col-md-12" onblur="cek6()" onfocus="cek6()" onchange="cek6()">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign6"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign6" style="color:red; display:none;"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" id="white1">
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-usd"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="number" placeholder="Harga Pokok" name="gl_harga_pokok" id="gl_harga_pokok" required class="col-md-12" onblur="cek7()" onfocus="cek7()" onchange=" cek7()" min="1">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign7"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign7" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-usd"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="number" placeholder="Harga Jual" name="gl_harga_jual" id="gl_harga_jual" required class="col-md-12" onblur="cek8()" onchange="cek8()" onfocus="cek8()" min="1">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign8"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign8" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-gbp"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="number" placeholder="PPN" name="gl_ppn" id="gl_ppn" required class="col-md-12" min="1" onblur="cek9()" onchange="cek9()" onfocus="cek9()">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign9"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign9" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-tag"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="number" placeholder="Diskon" name="gl_diskon" id="gl_diskon" required min="1" class="col-md-12" onchange="cek10()" onblur="cek10()" onfocus="cek10()">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign10"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign10" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <select name="gl_kategori" id="gl_kategori" class="col-md-12" onselect="cekkategori();" onblur="cekkategori();" onfocus="cekkategori();">
                                <option selected disabled>- Pilih Kategori -</option>
                                <option value="komedi">Komedi</option>
                                <option value="komputer">Komputer</option>
                                <option value="horor">Horor</option>
                                <option value="novel">Novel</option>
                                <option value="komik">Komik</option>
                                <option value="sejarah">Sejarah</option>
                                <option value="kamus">Kamus</option>
                                <option value="ekonomi">Ekonomi</option>
                                <option value="kesehatan">Kesehatan</option>
                                <option value="arsitektur">Arsitektur</option>
                                <option value="desain">Design</option>
                            </select>
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign12"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign12" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows" id="space">
                        <div class="col-md-1" id="in-space">
                            <span class="glyphicon glyphicon-picture"></span>
                        </div>
                        <div class="col-md-10" id="in-space">
                            <input type="file" name="gl_gambar_buku" id="gl_gambar_buku"  class="col-md-12" min="1" onblur="cekfile();" onchange="cekfile();" onfocus="cekfile();">
                        </div>
                        <div class="col-md-1" id="ok">
                            <span class="glyphicon glyphicon-ok-sign" id="ok-sign11"></span>
                            <span class="glyphicon glyphicon-remove-sign" id="rm-sign11" style="color:red; display:none;"></span>
                        </div>
                    </div>
                    <div class="rows">
                        <div class="col-md-1"></div>
                        <div class="col-md-11">
                            <span class="txt" style="font-size:15px;">Ukuran Foto Maksimal 2 Mb.</span>
                            <div id="msgfile" style="font-size:15px;font-style:italic;"></div>
                        </div>
                    </div>
                    
                </div>
                </div>   
                <div class="col-md-12" id="login-bawah">
                    <div class="rows">
                        <div class="col-md-12">
                        <textarea placeholder="Sinopsis Buku" name="gl_sinopsis" id="gl_sinopsis"></textarea>
                            </div>
                    </div>
                    </div>
                <div class="col-md-12" id="login-bawah">
                    <div class="rows" id="space2">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary col-md-12" id="btn-sub"><span class="glyphicon glyphicon-send"></span> &nbsp; Simpan</button>
                            <button type="submit" class="btn btn-primary col-md-12" disabled style="display:none;" id="btn-dis"><span class="glyphicon glyphicon-send"></span> &nbsp; Simpan</button>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
                </div>
        </div>
        <div class="full">
            <div class="container-fluid">
                <div class="rows">
                    <div class="col-md-12" id="content-full">
                        <div class="rows">
                            <div class="col-md-12">
                                <div class="rows">
                                    <div class="col-md-4" id="pad-top2">
                                        <div class="rows">
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom" id="tambah" title="Tambah Data">
                             <span class="glyphicon glyphicon-plus"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Print Data" id="print" onclick="window.print();">
                             <span class="glyphicon glyphicon-print"></span>
                         </div>
                         <div class="col-md-2 tip" data-toggle="tooltip" data-placement="bottom"  title="Refresh" id="refresh" onclick="window.location.reload()">
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
                    </div>
                                    </div>
                                    <div class="col-md-4" id="search">
                                        <form target="_self" enctype="multipart/form-data" name="cari" method="get">
                                    <input type="search" name="sch_cari" id="sch_cari" placeholder="Search" class="col-md-10">
                                    <button type="submit" class="btn"><span class="glyphicon glyphicon-search"></span></button>
                                </form>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="config_buku.php"><button class="btn btn-primary tip" data-toggle="tooltip" data-placement="bottom" title="Normal" id="tutup-full"><span class="glyphicon glyphicon-resize-small"></span></button></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rows">
                    <div class="col-md-12" id="content-full">
                        <table class="table table-striped">
                            <tr>
                                <th>No.</th>
                                <th>Cover</th>
                                <th>Judul</th>
                                <th>ISBN</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Stok</th>
                                <th>Harga Pokok</th>
                                <th>Harga Jual</th>
                                <th>PPN</th>
                                <th>Diskon</th>
                                <th>Option</th>
                            </tr>
                            <?php
                        $gl_jml_full = mysql_num_rows($gl_sql_full);
                        if($gl_jml_full >0){
                            $gl_no_full =1;
                            while ($gl_fetch_full = mysql_fetch_array($gl_sql_full)){
                                if($gl_no_full%2==0){
                                    $gl_warna_full = "#f8f8f8";
                                }else{
                                    $gl_warna_full ="white";
                                }
                            
                        ?>
                        <tr bgcolor="<?php echo $gl_warna_full; ?>">
                            <td><?php echo $gl_no_full; ?></td>
                            <td><img src="../pic/book/<?php echo $gl_fetch_full['gambar_buku']; ?>" class="img-cover"></td>
                            <td><?php echo $gl_fetch_full['judul']; ?></td>
                            <td><?php echo $gl_fetch_full['noisbn']; ?></td>
                            <td><?php echo $gl_fetch_full['penulis']; ?></td>
                            <td><?php echo $gl_fetch_full['nama_distributor']; ?></td>
                            <td><?php echo $gl_fetch_full['tahun']; ?></td>
                            <td><?php echo $gl_fetch_full['stok']; ?></td>
                            <td><?php echo"Rp ".number_format($gl_fetch_full['harga_pokok'],0,',','.'); ?></td>
                            <td><?php echo"Rp ".number_format($gl_fetch_full['harga_jual'],0,',','.'); ?></td>
                            <td><?php echo $gl_fetch_full['ppn']; ?> %</td>
                            <td><?php echo $gl_fetch_full['diskon']." %"; ?></td>
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
														<a href="config_buku.php?id_det_buku=<?php echo $gl_fetch_full['id_buku']; ?>">
															<i class="glyphicon glyphicon-folder-open"></i> &nbsp; Detail
														</a>
													</li>
													<li>
														<a href="config_edt_buku.php?id_edt_buku=<?php echo $gl_fetch_full['id_buku']; ?>">
															<i class="glyphicon glyphicon-pencil"></i> &nbsp; Edit
														</a>
													</li>
													<li>
														<a href="config_buku.php?id_hps_buku=<?php echo $gl_fetch_full['id_buku']; ?>" onClick="return confirm('Apakah anda yakin ingin menghapus data ini ?');"> 
															<i class="glyphicon glyphicon-trash"></i> &nbsp; Delete
														</a>
													</li>
													
												</ul>
											</div></td>
                        </tr>
                        <?php
                                $gl_no_full++;
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
                            <td><?php echo $gl_fetch['nama_distributor']; ?></td>
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