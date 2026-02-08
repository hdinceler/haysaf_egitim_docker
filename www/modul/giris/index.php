<?php
    $dugmeler = [
        "lgs-calisma-plani-hazirla"    => "Çalışma Plânı Hazırla",
        "lgs-ye-kac-gun-kaldi"         => "LGS'ye Kaç Gün Kaldı",
        "lgs-puan-simulasyonu"         => "Puan Simülasyonu",
        "lgs-takvimi"                  => "LGS Takvimi",
        "lgs-tercih-robotu"            => "Tercih Robotu",
        "puanima-gore-okullar"         => "Puanıma Göre Okullar",
        "yuzdelik-dilime-gore-okullar" => "Yüzdelik Dilime Göre Okullar",
 
        "il-bazli-okul-puanlari"       => "İl Bazlı Okul Puanları",
        "ilce-bazli-okul-puanlari"     => "İlçe Bazlı Okul Puanları",
        "okul-turune-gore-puanlar"     => "Okul Türüne Göre Puanlar",
 
        "anadolu-liseleri"             => "Anadolu Liseleri",
        "fen-liseleri"                 => "Fen Liseleri",
        "sosyal-bilimler-liseleri"     => "Sosyal Bilimler Liseleri",
        "mesleki-teknik-liseler"       => "Mesleki ve Teknik Liseler",
        "imam-hatip-liseleri"          => "İmam Hatip Liseleri",
 
        "gecmis-yil-taban-puanlari"    => "Geçmiş Yıl Taban Puanları",
        "son-3-yil-puan-karsilastirma" => "Son 3 Yıl Puan Karşılaştırma",
        "kontenjan-bilgileri"          => "Kontenjan Bilgileri",
 
        "okul-basari-siralamalari"     => "Okul Başarı Sıralamaları",
        "en-dusuk-yuzdelik"            => "Yerleşen En Düşük Yüzdelik",
        "en-yuksek-yuzdelik"           => "Yerleşen En Yüksek Yüzdelik",
 
        "tercih-listesi-olustur"       => "Tercih Listesi Oluştur",
        "tercih-simulasyonu"           => "Tercih Simülasyonu",
        "tercih-kontrol-araci"         => "Tercih Kontrol Aracı",
    ];

?>
  <!-- <style><?php 
//  echo minify_css(file_get_contents(__DIR__ . "/sprite.css"));
  ?></style> -->
<link rel="stylesheet" href="/public/css/sprite.css">
<div class="row">
    <?php foreach ($dugmeler as $key => $dugme): ?>

 
            <div class="col s6 m6 l4 padding-small center">
                <a href="/<?= $key ?>" class="button block border">
                    <div class="row sprite <?= $key ?>"></div>
                    <span class="row large " ><?= $dugme ?></span>
                </a>
            </div>
 

    <?php endforeach; ?>
</div>
