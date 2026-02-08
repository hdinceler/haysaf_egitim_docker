
<div class="container" style="display:flex; align-items:center">
  <section style="width:100%">

    <!-- LOGO -->
    <div class="" style="text-align:center; margin-bottom:2rem">
      <h2 class="black" style="font-weight:800">2026 LGS</h2>
      <div style="font-size:1.2rem">
        Liselere Geçiş Sistemi
      </div>

      <div style="font-size:.9rem; opacity:.8">
        14 Haziran 2026 Pazar – 09:30
      </div>
    </div>

    <!-- COUNTDOWN -->
    <div class="grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:.75rem; margin-bottom:1.25rem">
      <div class="btn" id="gun"></div>
      <div class="btn" id="saat"></div>
      <div class="btn" id="dakika"></div>
      <div class="btn" id="saniye"></div>
    </div>

    <!-- PROGRESS -->
    <div>
      <div style="position:relative; height:18px; border:2px solid #000">

        <!-- GERÇEK İLERLEME -->
        <div id="progress"
             style="height:100%; width:0%; background:#FFD500; transition:.5s">
        </div>

        <!-- GÜNLÜK HEDEF ÇİZGİSİ -->
        <div id="hedefCizgi"
             style="position:absolute; top:-6px; width:2px; height:30px; background:#000">
        </div>

      </div>

      <div id="progressText"
           style="margin-top:.75rem; text-align:center; font-weight:700">
      </div>
    </div>

  </section>
</div>

<script>
/* SABİT TARİHLER */
const baslangic = new Date("2025-09-01T00:00:00").getTime();
const bitis = new Date("2026-06-14T09:30:00").getTime();

function guncelle(){
  const simdi = Date.now();
  const kalan = bitis - simdi;
  const toplam = bitis - baslangic;
  const gecen = simdi - baslangic;

  if(kalan <= 0){
    document.getElementById("progress").style.width = "100%";
    document.getElementById("progress").style.background = "#c40000";
    document.getElementById("progressText").innerText = "SINAV BAŞLADI";
    return;
  }

  /* GERİ SAYIM */
  const gun = Math.floor(kalan / (1000*60*60*24));
  const saat = Math.floor((kalan / (1000*60*60)) % 24);
  const dakika = Math.floor((kalan / (1000*60)) % 60);
  const saniye = Math.floor((kalan / 1000) % 60);

  gunEl = document.getElementById("gun");
  saatEl = document.getElementById("saat");
  dakikaEl = document.getElementById("dakika");
  saniyeEl = document.getElementById("saniye");

  gunEl.innerHTML = `${gun}<br>Gün`;
  saatEl.innerHTML = `${saat}<br>Saat`;
  dakikaEl.innerHTML = `${dakika}<br>Dakika`;
  saniyeEl.innerHTML = `${saniye}<br>Saniye`;

  /* YÜZDELER */
  const gercekYuzde = Math.min(100, (gecen / toplam) * 100);
  const hedefYuzde = Math.min(100, ((simdi - baslangic) / toplam) * 100);

  /* BAR */
  const bar = document.getElementById("progress");
  bar.style.width = gercekYuzde.toFixed(2) + "%";
  bar.style.background = gun <= 30 ? "#c40000" : "#16bd37ff";

  /* HEDEF ÇİZGİSİ */
  document.getElementById("hedefCizgi").style.left =
    `calc(${hedefYuzde}% - 1px)`;

  /* DURUM METNİ */
  const fark = gercekYuzde - hedefYuzde;
  let durum = "";

 

  document.getElementById("progressText").innerText =
    `Zamanın  %${gercekYuzde.toFixed(1)} geçti`;
}

guncelle();
setInterval(guncelle, 1000);
</script>

 