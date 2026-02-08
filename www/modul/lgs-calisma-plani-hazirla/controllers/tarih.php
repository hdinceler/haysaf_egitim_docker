<?php
if( PLAN::post_edildi('sifirla') ) PLAN::sifirla();
$mesajlar['plan'] =PLAN::mesajlar_default();

if (PLAN::post_edildi('tarih_araligi_belirle')) {

    $baslangic_ok = PLAN::tarih_alani_isle(
        'tarih_baslangic',
        'Başlangıç Tarihi',
        $_POST['tarih_baslangic'] ?? '',
        $mesajlar
    );

    $bitis_ok = PLAN::tarih_alani_isle( 'tarih_bitis', 'Bitiş Tarihi',  $_POST['tarih_bitis'] ?? '',  $mesajlar );
    if ($baslangic_ok && $bitis_ok) {
        $aralik = PLAN::tarih_araligi_kontrol( $_POST['tarih_baslangic'], $_POST['tarih_bitis']);
        if ($aralik !== true) {
            PLAN::tarih_temizle('tarih_bitis');
            $mesajlar['plan']['tarih_bitis'] = PLAN::mesaj('tarih_bitis',$aralik,'error');
        }
    }

} else {

    foreach (['tarih_baslangic', 'tarih_bitis'] as $key) {
        if (PLAN::tarih_gecerli_girilmis($key)) {
            $mesajlar['plan'][$key] = PLAN::mesaj($key,PLAN::tarih_tr_session($key),'success' );
        } else {
            PLAN::tarih_temizle($key);
            $mesajlar['plan'][$key] = PLAN::mesaj( $key, ucfirst(str_replace('_', ' ', $key)) . ' girilmemiş', 'error' );
        }
    }
}

$durum_tarih_araligi= PLAN::tarih_araligi_getir();
if($durum_tarih_araligi['type']==='success'){
    $mesajlar['plan']['gun_sayisi']=PLAN::mesaj('gun_sayisi',$durum_tarih_araligi['result'] ,'success');
}else{
    $mesajlar['plan']['gun_sayisi']=PLAN::mesaj('gun_sayisi',$durum_tarih_araligi['result'] ,'error');
}
// if( $mesajlar['plan']['tarih_baslangic'] )
// debug('',$mesajlar['plan']['tarih_baslangic']);

$kontrol=PLAN::tarih_araligi_dogru_girilmis();
// debug('tarih aralığı',$kontrol);
// debug('',$_SESSION);
