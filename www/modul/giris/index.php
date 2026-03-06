<?php
$dugmeler = [
    "ogrenci"        => ["label" => "Öğrenci Yönetimi",       "aciklama" => "Öğrenci bilgileri, devamsızlık ve ders programı takibi."],
    "veli-portal"             => ["label" => "Veli Portalı",           "aciklama" => "Sınav sonuçları, rehberlik notları ve iletişim."],
    "ogretmen-modulu"         => ["label" => "Öğretmen Modülü",       "aciklama" => "Ders planı, not verme ve sınav yönetimi."],
    "idare-modulu"            => ["label" => "İdare Modülü",          "aciklama" => "Raporlama ve okul bazlı yönetim paneli."],
    "servis-takibi"           => ["label" => "Servis Takibi",         "aciklama" => "Servis aracı ve rota takibi."],
    "sinav-deneme-yonetimi"  => ["label" => "Sınav / Deneme Yönetimi","aciklama" => "Sınav oluşturma, deneme takibi ve sonuç analizi."],
    "rehberlik-saglik"       => ["label" => "Rehberlik & Sağlık",   "aciklama" => "Psikolojik danışmanlık ve sağlık notları."],
    "sozlesme-izin-yonetimi" => ["label" => "Sözleşme / İzin Yönetimi","aciklama" => "Veli izinleri ve hukuki belgeler."],
    "bildirim-duyuru"        => ["label" => "Bildirim / Duyuru",    "aciklama" => "Dahili mesaj paneli ile bilgilendirme."],
    "kantin-menu"             => ["label" => "Kantin Menüsü",        "aciklama" => "Günlük menü ve fiyat bilgilerini görüntüle."],
    "kantin-siparis"          => ["label" => "Alım/Satım",   "aciklama" => "Alım Satım İhalelerini yönet."],
    "paket-hizmetler"         => ["label" => "Opsiyonel Paketler",   "aciklama" => "Ekstra yiyecek/içecek paketlerini ve aboneliklerini yönet."],
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