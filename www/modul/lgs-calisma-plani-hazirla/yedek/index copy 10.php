<?php
$sql = "
SELECT 
    d.ders_id,
    d.ders_adi,
    u.unite_id,
    u.unite_adi,
    ROUND((u.soru_2020+u.soru_2021+u.soru_2022+u.soru_2023+u.soru_2024+u.soru_2025)/6,2) AS ortalama
FROM dersler d
LEFT JOIN uniteler u ON u.ders_id = d.ders_id
ORDER BY d.sira, u.unite_id
";

$rows = DB::readRaw($sql);

$dersler = [];

foreach($rows as $r){
    $id = $r['ders_id'];
    if(!isset($dersler[$id])){
        $dersler[$id] = [
            'ders_adi' => $r['ders_adi'],
            'uniteler' => []
        ];
    }
    if($r['unite_id']){
        $dersler[$id]['uniteler'][] = [
            'unite_id' => $r['unite_id'],
            'unite_adi' => $r['unite_adi'],
            'ortalama' => $r['ortalama']
        ];
    }
}


$bugun = date('Y-m-d');
$bitisDefault = '2026-06-13';

$sql="SELECT * FROM gunler ORDER BY gun_id asc";
$gunler = DB::readRaw($sql);
?>

<style>
  .unite-grid{
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap:8px;
}
.unite-box{
    border:1px solid #ddd;
    padding:6px;
    border-radius:4px;
    background:#fafafa;
}

.istisna-badge{
    display:inline-block;
    background:#eee;
    border:1px solid #ccc;
    border-radius:4px;
    padding:3px 6px;
    margin:2px;
    font-size:12px;
    white-space:nowrap;
}
.istisna-badge button{
    border:none;
    background:#c00;
    color:#fff;
    margin-left:4px;
    cursor:pointer;
    padding:0 4px;
    border-radius:3px;
}
</style>

<!-- ================== 1. ADIM ================== -->

<div class="margin-top border">
    <div class="padding-small">

        <h4 class="center text-indigo margin-bottom">1. ADIM: Tarih Aralığını Belirle</h4>

        <div class="row-padding small margin-top">
            <div class="col s6">
                <label>Başlangıç Tarihi</label>
                <input type="date" class="input border" id="baslangic" value="<?= $bugun ?>">
            </div>
            <div class="col s6">
                <label>Bitiş Tarihi</label>
                <input type="date" class="input border" id="bitis" value="<?= $bitisDefault ?>">
            </div>
        </div>

        <div class="center small margin-top">
            <b>Toplam Süre: <span id="gunSayisi">0</span> gün</b><br>
            <b>Net Çalışma: <span id="netGun">0</span> gün</b>
        </div>

        <hr>

        <h4 class="center text-indigo margin-bottom">İstisna Günler</h4>

        <div class="row-padding small">
            <div class="col s6">
                <input type="date" id="istisnaTarih" class="input border">
            </div>
            <div class="col s6">
                <button type="button" class="button green" onclick="istisnaEkle()">+ Gün Ekle</button>
            </div>
        </div>

        <div class="center margin-top">
            <button class="button blue small" onclick="varsayilanlariGetir()">Varsayılanları Getir</button>
            <button class="button red small" onclick="hepsiniSil()">Hepsini Sil</button>
        </div>

        <div id="istisnaListe" class="margin-top"></div>

    </div>
</div>

<!-- ================== 2. ADIM ================== -->

<div class="margin-top border">
    <div class="padding-small">

        <h4 class="center text-indigo margin-bottom">2. ADIM: Günleri ve Saatleri Belirle</h4>

        <div class="row-padding center small">

            <?php foreach($gunler as $g): ?>

                <div class="col s6 m2 l2">
                    <div class="bold"><?= htmlspecialchars($g['kisaltma']) ?></div>

                    <select class="select border margin-top gunSaat">
                        <?php for($i=0;$i<=12;$i++): ?>
                            <option value="<?= $i ?>" <?= $i==3 ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

            <?php endforeach; ?>

        </div>

        <div class="center margin-top padding bold">
            Toplam Planlanan Çalışma: <span id="toplamSaat">0</span> saat
        </div>

    </div>
</div>

<!-- ================== 3. ADIM ================== -->

<div class="margin-top border">
<div class="padding-small">

<h4 class="center text-indigo margin-bottom">3. ADIM: Ders ve Ünite Dağılımı</h4>

<div class="w3-light-grey w3-round">
    <div id="kalanBar" class="w3-green w3-round" style="height:24px;width:100%"></div>
</div>
<div class="center bold margin-bottom">
    Kalan Süre: <span id="kalanSaatYazi">0</span> saat
</div>

<?php foreach($dersler as $ders_id=>$d): ?>

<div class="border padding margin-bottom">
    <b><?= htmlspecialchars($d['ders_adi']) ?></b>
    <div>
        <input type="range" min="0" value="0" class="dersSlider" data-ders="<?= $ders_id ?>">
        <span class="dersSaatYazi">0</span> saat
    </div>

    <div class="margin-left unite-grid">

    <?php foreach($d['uniteler'] as $u): ?>

        <div class="small unite-box">
            <?= htmlspecialchars($u['unite_adi']) ?> (<?= $u['ortalama'] ?>)
            <br>
            <input type="range" min="0" value="0" 
                   class="uniteSlider" 
                   data-ders="<?= $ders_id ?>">
            <span class="uniteSaatYazi">0</span> s
        </div>

    <?php endforeach; ?>

    </div>
</div>

<?php endforeach; ?>

</div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    var baslangic = document.getElementById("baslangic");
    var bitis = document.getElementById("bitis");
    var gunSayisiSpan = document.getElementById("gunSayisi");
    var netGunSpan = document.getElementById("netGun");
    var istisnaListe = document.getElementById("istisnaListe");
    var toplamSaatSpan = document.getElementById("toplamSaat");

    var istisnaGunler = new Map();

    var varsayilanTatil = {
        "2026-01-01": "Yılbaşı",
        "2026-04-23": "23 Nisan",
        "2026-05-01": "1 Mayıs",
        "2026-05-19": "19 Mayıs",
        "2026-07-15": "15 Temmuz",
        "2026-08-30": "30 Ağustos",
        "2026-10-29": "29 Ekim",
        "2026-02-17": "Ramazan B.",
        "2026-02-18": "Ramazan B.",
        "2026-02-19": "Ramazan B.",
        "2026-06-27": "Kurban B.",
        "2026-06-28": "Kurban B.",
        "2026-06-29": "Kurban B.",
        "2026-06-30": "Kurban B."
    };

    function trTarih(iso){
        var p = iso.split("-");
        return p[2]+"."+p[1]+"."+p[0];
    }

    window.varsayilanlariGetir = function(){
        istisnaGunler.clear();
        for (var t in varsayilanTatil){
            istisnaGunler.set(t, varsayilanTatil[t]);
        }
        listeyiYenile();
    }

    window.hepsiniSil = function(){
        if(!confirm("Tüm istisna günler silinsin mi?")) return;
        istisnaGunler.clear();
        listeyiYenile();
    }

    function gunleriHesapla() {
        if (!baslangic.value || !bitis.value) return;

        var d1 = new Date(baslangic.value);
        var d2 = new Date(bitis.value);

        var toplam = Math.floor((d2 - d1) / 86400000) + 1;
        if (toplam < 0) toplam = 0;

        gunSayisiSpan.innerText = toplam;

        var dusulecek = 0;
        istisnaGunler.forEach(function(_, t){
            var dt = new Date(t);
            if (dt >= d1 && dt <= d2) dusulecek++;
        });

        var net = toplam - dusulecek;
        if (net < 0) net = 0;

        netGunSpan.innerText = net;

        toplamSaatiHesapla();
    }

    function listeyiYenile() {
        istisnaListe.innerHTML = "";
        Array.from(istisnaGunler.keys()).sort().forEach(function(t){
            var label = istisnaGunler.get(t);
            var span = document.createElement("span");
            span.className = "istisna-badge";
            span.innerHTML = trTarih(t) + " ("+label+") <button onclick=\"istisnaSil('"+t+"')\">×</button>";
            istisnaListe.appendChild(span);
        });
        gunleriHesapla();
    }

    window.istisnaEkle = function () {
        var t = document.getElementById("istisnaTarih").value;
        if (!t) return;

        var d = new Date(t);
        var d1 = new Date(baslangic.value);
        var d2 = new Date(bitis.value);

        if (d < d1 || d > d2){
            alert("Seçilen tarih aralık içinde olmalı!");
            return;
        }

        istisnaGunler.set(t, "Özel");
        listeyiYenile();
    }

    window.istisnaSil = function (t) {
        istisnaGunler.delete(t);
        listeyiYenile();
    }

    function toplamSaatiHesapla(){
        if (!baslangic.value || !bitis.value) return;

        var start = new Date(baslangic.value);
        var end = new Date(bitis.value);

        var haftalik = {};
        document.querySelectorAll(".gunSaat").forEach(function(sel, i){
            haftalik[i] = parseInt(sel.value || 0);
        });

        var toplamSaat = 0;

        for (var d = new Date(start); d <= end; d.setDate(d.getDate()+1)) {
            var iso = d.toISOString().slice(0,10);
            if (istisnaGunler.has(iso)) continue;

            var jsGun = d.getDay();
            var trIndex = (jsGun + 6) % 7;

            toplamSaat += (haftalik[trIndex] || 0);
        }

        toplamSaatSpan.innerText = toplamSaat;
    }

    baslangic.addEventListener("change", gunleriHesapla);
    bitis.addEventListener("change", gunleriHesapla);

    document.querySelectorAll(".gunSaat").forEach(function(sel){
        sel.addEventListener("change", toplamSaatiHesapla);
    });

    varsayilanlariGetir(); // ilk yüklemede otomatik

// ===================== 3. ADIM GERÇEK KOTA MOTORU =====================

var toplamSaatGlobal = 0;
var kalanSpan = document.getElementById("kalanSaatYazi");
var kalanBar = document.getElementById("kalanBar");

function guncelleToplamSaat(){
    toplamSaatGlobal = parseInt(document.getElementById("toplamSaat").innerText || 0);

    // Ders slider maxlarını güncelle
    document.querySelectorAll(".dersSlider").forEach(sl=>{
        sl.max = toplamSaatGlobal;
    });

    // Kalanı yeniden hesapla
    dersToplamlariniYenidenHesapla();
}

setInterval(guncelleToplamSaat, 500);

// ===================== DERS KOTALARI =====================

function dersToplamlariniYenidenHesapla(){
    var toplam = 0;
    document.querySelectorAll(".dersSlider").forEach(s=>{
        toplam += parseInt(s.value||0);
    });

    var kalan = toplamSaatGlobal - toplam;
    if(kalan < 0) kalan = 0;

    kalanSpan.innerText = kalan;

    if(toplamSaatGlobal > 0){
        kalanBar.style.width = (kalan / toplamSaatGlobal * 100) + "%";
    }else{
        kalanBar.style.width = "0%";
    }
}

// Ders sliderları
document.querySelectorAll(".dersSlider").forEach(function(slider){

    slider.addEventListener("input", function(){

        var yeniDeger = parseInt(this.value||0);
        var onceki = parseInt(this.dataset.prev || 0);

        // toplam kontrolü
        var toplam = 0;
        document.querySelectorAll(".dersSlider").forEach(s=>{
            toplam += parseInt(s.value||0);
        });

        if(toplam > toplamSaatGlobal){
            this.value = onceki;
            return;
        }

        this.dataset.prev = this.value;

        // yazıyı güncelle
        this.parentElement.querySelector(".dersSaatYazi").innerText = this.value;

        // Eğer kota AZALDIYSA → üniteleri orantılı düşür
        if(yeniDeger < onceki){
            uniteOrantiliDusur(this.dataset.ders, yeniDeger);
        }

        dersToplamlariniYenidenHesapla();
    });

    slider.dataset.prev = 0;
});

// ===================== ÜNİTE KOTALARI =====================

function uniteToplam(dersId){
    var t = 0;
    document.querySelectorAll('.uniteSlider[data-ders="'+dersId+'"]').forEach(s=>{
        t += parseInt(s.value||0);
    });
    return t;
}

function uniteOrantiliDusur(dersId, yeniLimit){

    var unites = Array.from(document.querySelectorAll('.uniteSlider[data-ders="'+dersId+'"]'));

    var toplam = uniteToplam(dersId);

    if(toplam <= yeniLimit) return; // sorun yok

    if(toplam == 0) return;

    var oran = yeniLimit / toplam;

    var yeniToplam = 0;

    unites.forEach(function(s){
        var eski = parseInt(s.value||0);
        var yeni = Math.floor(eski * oran);
        s.value = yeni;
        s.parentElement.querySelector(".uniteSaatYazi").innerText = yeni;
        yeniToplam += yeni;
    });

    // Yuvarlama farkı varsa dağıt
    var fark = yeniLimit - yeniToplam;
    var i = 0;
    while(fark > 0 && unites.length > 0){
        var s = unites[i % unites.length];
        s.value = parseInt(s.value) + 1;
        s.parentElement.querySelector(".uniteSaatYazi").innerText = s.value;
        fark--;
        i++;
    }
}

// Ünite sliderları
document.querySelectorAll(".uniteSlider").forEach(function(slider){

    slider.addEventListener("input", function(){

        var dersId = this.dataset.ders;

        var dersSlider = document.querySelector('.dersSlider[data-ders="'+dersId+'"]');
        var dersLimit = parseInt(dersSlider.value||0);

        var toplam = uniteToplam(dersId);

        if(toplam > dersLimit){
            // geri al
            this.value = this.dataset.prev || 0;
            return;
        }

        this.dataset.prev = this.value;

        this.parentElement.querySelector(".uniteSaatYazi").innerText = this.value;
    });

    slider.dataset.prev = 0;
});


});
</script>
