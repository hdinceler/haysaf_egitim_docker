<div class=" center">

  <!-- LOGO -->
  <div class="margin-bottom">
    <h2 class="">2026 LGS
    Liselere Geçiş Sistemi Sınavına</h2>
    <div class="small opacity">
      14 Haziran 2026 Pazar – 09:30
    </div>
  </div>

  <!-- COUNTDOWN -->
  <div class="row-padding margin-bottom">

    <div class="col s12m3 l3 padding">
      <div class="border center padding">
        <div id="gun" class="xxlarge bold"></div>
        <div class="small opacity">GÜN</div>
      </div>
    </div>

    <div class="col padding s12m3 l3">
      <div class="border  center padding">
        <div id="saat" class="xxlarge bold"></div>
        <div class="small opacity">SAAT</div>
      </div>
    </div>

    <div class="col padding s12m3 l3">
      <div class="border center padding">
        <div id="dakika" class="xxlarge bold"></div>
        <div class="small opacity">DAKİKA</div>
      </div>
    </div>

    <div class="col padding s12m3 l3">
      <div class="border center padding">
        <div id="saniye" class="xxlarge bold"></div>
        <div class="small opacity">SANİYE</div>
      </div>
    </div>

  </div>

  <!-- PROGRESS -->
  <div class="margin-top">

    <div class="border"
         style="height:22px; position:relative">

      <div id="progress"
           class="border black"
           style="height:100%; width:0%">
      </div>

      <!-- HEDEF ÇİZGİSİ -->
      <div id="hedefCizgi"
           class=""
           style="position:absolute; top:-12px; width:3px;background-color:black; height:44px">
      </div>
    </div>

    <div id="progressText"
         class="center margin-top large bold">
    </div>

  </div>

</div>

<script>
const baslangic = new Date("2025-09-01T00:00:00").getTime();
const bitis     = new Date("2026-06-14T09:30:00").getTime();

function guncelle(){
  const simdi   = Date.now();
  const kalan  = bitis - simdi;
  const toplam = bitis - baslangic;
  const gecen  = simdi - baslangic;

  const bar = document.getElementById("progress");

  if(kalan <= 0){
    bar.style.width = "100%";
    document.getElementById("progressText").innerText = "SINAV BAŞLADI";
    return;
  }

  const gun    = Math.floor(kalan / (1000*60*60*24));
  const saat   = Math.floor((kalan / (1000*60*60)) % 24);
  const dakika = Math.floor((kalan / (1000*60)) % 60);
  const saniye = Math.floor((kalan / 1000) % 60);

  gunEl = document.getElementById("gun").innerText = gun;
  document.getElementById("saat").innerText   = saat;
  document.getElementById("dakika").innerText = dakika;
  document.getElementById("saniye").innerText = saniye;

  const yuzde = Math.min(100, (gecen / toplam) * 100);
  bar.style.width = yuzde.toFixed(2) + "%";

  document.getElementById("hedefCizgi").style.left =
    `calc(${yuzde}% - 1px)`;

  document.getElementById("progressText").innerText =
    `Eğitim öğretim yılı başlangıcından bu yana geçen zaman: %${yuzde.toFixed(1)}`;
    console.log( yuzde.toFixed(2) );
    
}

guncelle();
setInterval(guncelle, 1000);
</script>
