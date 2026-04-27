<?php
$dugmeler = [
    "kisa-ders-notlari"        => [
        "label" => "Kısa Ders Notları",
        "aciklama" => "LGS Fen Bilimleri için konu özetleri ve hızlı tekrar notları."
    ],
    "kazanim-analizi"          => [
        "label" => "MEB Kazanım Analizi",
        "aciklama" => "MEB müfredatına göre kazanım bazlı öğrenme ve eksik tespiti."
    ],
    "basari-siralamasi"        => [
        "label" => "Başarı Sıralaması",
        "aciklama" => "Öğrencinin performansını diğer kullanıcılarla karşılaştırma."
    ],
    "net-hesaplama"            => [
        "label" => "Net Hesaplama",
        "aciklama" => "Doğru-yanlış üzerinden otomatik net hesaplama sistemi."
    ],
    "lgs-siralama-simulasyonu" => [
        "label" => "LGS Sıralama Simülasyonu",
        "aciklama" => "Gerçek sınav verilerine benzer tahmini LGS sıralama simülasyonu."
    ],
    "zayif-nokta-analizi"      => [
        "label" => "Zayıf Nokta Analizi",
        "aciklama" => "Yanlış yapılan kazanımları tespit ederek eksik alanları gösterir."
    ],
    "net-arttirma"             => [
        "label" => "Net Arttırma Planı",
        "aciklama" => "Öğrenciye özel çalışma planı ile net artırmaya yönelik öneriler."
    ],
];
?>

HAYSAF: Haberleşme Akademik Yönetim Sosyal Ağ Finans
<style>.button .modul-aciklama {
    display: block;
    font-size: 0.8rem;
    color: #555;
    margin-top: 4px;
}</style>

<link rel="stylesheet" href="/public/css/sprite.css">

<div class="row">
    <?php foreach ($dugmeler as $key => $modul): ?>
        <div class="col s6 m6 l4 padding-small center">
            <a href="/<?= $key ?>" class="button block border">
                <!-- İkon -->
                <div class="sprite bg-<?= $key ?>"></div>

                <!-- Başlık -->
                <span class="large"><?= $modul['label'] ?></span>

                <!-- Açıklama -->
                <small class="modul-aciklama"><?= $modul['aciklama'] ?></small>
            </a>
        </div>
    <?php endforeach; ?>
</div>