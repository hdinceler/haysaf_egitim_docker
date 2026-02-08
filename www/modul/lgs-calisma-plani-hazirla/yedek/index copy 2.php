<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>LGS Çalışma Programı</title>

<link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

<style>
@media print {
  .no-print { display:none }
  body { font-size:11px }
}
</style>
</head>

<body class="w3-light-grey">

<div class="w3-content w3-padding" style="max-width:1200px">

<h2 class="w3-center">LGS Çalışma Programı</h2>

<!-- TARİHLER -->
<div class="w3-card w3-padding w3-margin-bottom">
  <div class="w3-row-padding">
    <div class="w3-half">
      <label>Başlangıç Tarihi</label>
      <input id="baslangic" type="date" class="w3-input w3-border">
    </div>
    <div class="w3-half">
      <label>Sınav Tarihi</label>
      <input id="bitis" type="date" class="w3-input w3-border" value="2026-06-16">
    </div>
  </div>
</div>

<!-- DERSLER -->
<div id="dersler"></div>

<!-- TAKVİM -->
<h3 class="w3-margin-top">Takvim</h3>
<div id="takvim" class="w3-row-padding"></div>

<button onclick="window.print()"
        class="w3-button w3-black w3-margin-top no-print">
  PDF / Yazdır
</button>

</div>

<!-- STICKY BOTTOM -->
<div class="w3-bar w3-black w3-bottom w3-padding w3-small">
  <span id="toplamGun"></span> |
  <span id="planlananGun"></span> |
  <span id="bosGun"></span>
</div>

<script>
/* ==== ÖRNEK VERİ ==== */
const DATA = [
{
  id:"mat",
  ad:"Matematik",
  unite:[
    {ad:"Oran Orantı", s:[5,4,6,7]},
    {ad:"Problemler", s:[8,7,9,10]},
    {ad:"Üslü Sayılar", s:[4,3,5,4]}
  ]
},
{
  id:"turkce",
  ad:"Türkçe",
  unite:[
    {ad:"Paragraf", s:[10,11,9,12]},
    {ad:"Sözel Mantık", s:[3,4,2,3]}
  ]
}
];

/* ==== UI ==== */
const derslerEl = document.getElementById("dersler");

DATA.forEach(d=>{
  let html = `
  <div class="w3-card w3-padding w3-margin-bottom">
    <h3>${d.ad}</h3>

    <table class="w3-table w3-bordered w3-small">
      <tr class="w3-light-grey">
        <th>Ünite</th>
        <th>Soru</th>
        <th>Gün</th>
      </tr>
  `;

  d.unite.forEach((u,i)=>{
    const toplam = u.s.reduce((a,b)=>a+b,0);
    html+=`
      <tr>
        <td>${u.ad}</td>
        <td>${toplam}</td>
        <td>
          <select class="w3-select gun"
                  data-ders="${d.id}"
                  data-unite="${i}">
            ${[...Array(31)].map((_,i)=>`<option>${i}</option>`).join("")}
          </select>
        </td>
      </tr>
    `;
  });

  html+=`</table></div>`;
  derslerEl.innerHTML+=html;
});

/* ==== TARİH HESABI ==== */
function gunFarki(a,b){
  return Math.ceil((b-a)/(1000*60*60*24));
}

/* ==== ANA HESAPLAMA ==== */
function hesapla(){
  const bas = new Date(baslangic.value).getTime();
  const bit = new Date(bitis.value).getTime();
  if(!bas || !bit || bas>=bit) return;

  const toplamGun = gunFarki(bas,bit);

  let plan = [];

  document.querySelectorAll(".gun").forEach(s=>{
    const gun = Number(s.value);
    if(!gun) return;

    const ders = DATA.find(d=>d.id===s.dataset.ders);
    const unite = ders.unite[s.dataset.unite];

    for(let i=0;i<gun;i++){
      plan.push({ ders: ders.ad, unite: unite.ad });
    }
  });

  takvimOlustur(bas,bit,plan);

  /* Sticky info */
  document.getElementById("toplamGun").innerText =
    "Toplam Gün: "+toplamGun;

  document.getElementById("planlananGun").innerText =
    "Planlanan: "+plan.length;

  document.getElementById("bosGun").innerText =
    "Boşta: "+Math.max(0,toplamGun-plan.length);
}

/* ==== TAKVİM ==== */
function takvimOlustur(bas,bit,plan){
  const t = document.getElementById("takvim");
  t.innerHTML="";

  let planIndex = 0;
  const gunSayisi = gunFarki(bas,bit);

  for(let i=0;i<gunSayisi;i++){
    const tarih = new Date(bas + i*86400000);
    const gun = tarih.getDay();

    const tip =
      gun===0 ? "DENEME" :
      gun===6 ? "TEKRAR" : "";

    let icerik = "";

    /* ❌ DENEME günlerinde ünite ataması YOK */
    if(tip!=="DENEME" && planIndex < plan.length){
      icerik = `
        <div class="w3-tag w3-blue w3-margin-bottom">
          ${plan[planIndex].ders}
        </div>
        <div class="w3-small">
          ${plan[planIndex].unite}
        </div>
      `;
      planIndex++;
    }

    t.innerHTML += `
      <div class="w3-col s6 m3 l2 w3-margin-bottom">
        <div class="w3-card w3-padding w3-small
          ${tip==="DENEME"?"w3-pale-red":
            tip==="TEKRAR"?"w3-pale-yellow":""}">

          <div class="w3-bold">
            ${tarih.toLocaleDateString("tr-TR")}
          </div>

          ${tip ? `<div class="w3-tag w3-red w3-margin-bottom">${tip}</div>` : ""}

          ${icerik}
        </div>
      </div>
    `;
  }
}

/* ==== EVENTS ==== */
document.querySelectorAll("input,select")
  .forEach(e=>e.addEventListener("change",hesapla));
</script>

</body>
</html>
