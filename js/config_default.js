       $(function(){
                $(window).scroll(function(){
                    if($(window).scrollTop()>100){
            $('.backtotop').fadeIn('slow');
            $('.in-jumbo').fadeIn('slow');
        }else{
            $('.backtotop').fadeOut('slow');
            $('.in-jumbo').fadeOut('slow');
        }
                })
    
  });

$(function(){
   $('#tambah').click(function(){
      $('.long').css({
          display:'block',
      });
       $('.full').css({
           display:'none'
       });
   }); 
    $('#tutup-buku').click(function(){
      $('.long').css({
          display:'none',
      }); 
   }); 
    $('#tutup-buku1').click(function(){
      $('.long').css({
          display:'none',
      }); 
        $('.full').css({
           display:'block',
       })
   }); 
    $('#profil').click(function(){
        $('.profile').css({
            display:'block',
        });
    });
    $('#tutup-profil').click(function(){
        $('.profile').css({
            display:'none',
        })
    });
});


window.setTimeout("waktu()",1000);
function waktu(){
    var waktu = new Date();
    var jam = waktu.getHours();
    var meni = waktu.getMinutes();
    var detik = waktu.getSeconds();
    var hari = waktu.getDate();
    var bulan = waktu.getMonth();
    var tahun = waktu.getFullYear();
    window.setTimeout("waktu()",1000);
 document.getElementById('tgl').style.fontSize="20px";
    document.getElementById('tgl').style.textAlign="center";
    document.getElementById('tgl').innerHTML= hari +" / "+bulan+" / "+tahun+" <br> "+jam+":"+meni+":"+detik;  
}

window.setTimeout("time()",1000);
function time(){
    var waktu = new Date();
    var jam = waktu.getHours();
    var meni = waktu.getMinutes();
    var detik = waktu.getSeconds();
    var hari = waktu.getDate();
    var bulan = waktu.getMonth();
    var tahun = waktu.getFullYear();
    window.setTimeout("time()",1000);
 
    document.getElementById('tglsec').innerHTML=jam+" &nbsp:&nbsp "+meni+" &nbsp:&nbsp"+detik;  
}



