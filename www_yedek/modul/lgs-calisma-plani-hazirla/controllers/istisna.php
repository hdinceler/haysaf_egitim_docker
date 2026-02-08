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
PLAN::istisnalar_havuza_ekle($istisnalar_user);
$istisnalar_havuz=PLAN::istisnalar_havuz_getir();
$havuzda_olmayanlar=PLAN::istisna_havuzda_olmayanlar();
// debug('istisnalar_user', $istisnalar_user);
// debug('istisnalar_db',$istisnalar_db);
// debug('istisnalar_havuz',$istisnalar_havuz );
// unset($_SESSION['plan']['istisna']);
// debug('havuzda olamayanlar', $havuzda_olmayanlar);
$istisna_havuz_adet=PLAN::istisna_havuz_adet();
$mesajlar['plan']['gun_sayisi']=PLAN::mesaj('gun_sayisi',$istisna_havuz_adet, 'success');