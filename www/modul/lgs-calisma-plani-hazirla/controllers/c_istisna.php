<?php
// PLAN::sifirla();

$istisnalar_db=PLAN::istisnalar_db_getir(PLAN::tarih_baslangic() , PLAN::tarih_bitis() );
 $istisnalar_user=[
    '2026-03-12'=>'Ramazan bayramı benim',
    '2026-03-21'=>'Kendime tatil yaptım',
    '2026-03-20'=>'Bisiklet Turu',
    '2026-05-01'=>'kendi 1 mayıs',
    '2026-04-23'=>'kendi 23 nisan',
    ];
// PLAN::istisnalar_havuza_ekle($istisnalar_user);

// $toplam_gun_sayisi=PLAN::tarih_araligi_getir()['result'] ;
// $istisna_gun_sayisi=PLAN::istisna_havuz_adet();
// $net_gun_sayisi=$toplam_gun_sayisi - $istisna_gun_sayisi;
// $str_gun_sayisi="$net_gun_sayisi gün (Toplam $toplam_gun_sayisi  gün, $istisna_gun_sayisi istisna gün )";

// $net_gun_sayisi=$toplam_gun_sayisi- 
// $istisna_havuz=PLAN::istisnalar_havuz_getir();
// $istisna_havuzda_olmayanlar=PLAN::istisna_havuzda_olmayanlar();
// // debug('istisnalar_user', $istisnalar_user);
// // debug('istisnalar_db',$istisnalar_db);
// // debug('istisnalar_havuz',$istisnalar_havuz );
// // unset($_SESSION['plan']['istisna']);
// // debug('havuzda olamayanlar', $havuzda_olmayanlar);
// $istisna_havuz_adet=PLAN::istisna_havuz_adet();
//  $mesajlar['plan']['gun_sayisi']=PLAN::mesaj('gun_sayisi',$str_gun_sayisi, 'success');
//   debug('session',$_SESSION['plan']['gun_sayisi']);