<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>LGS Çalışma Programı</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<style>
/* Sadece W3 kullanıldı; belirginlik için küçük yardımcılar */
.sticky-bar{box-shadow:0 -4px 12px rgba(0,0,0,.2)}
.day{min-height:120px}
.unit-card{cursor:move}
@media print{
  .no-print{display:none}
  .day{page-break-inside:avoid}
}
</style>
</head>
<body class="w3-light-grey">

<div class="w3-container w3-margin">

<h2 class="w3-center">LGS Çalışma Programı</h2>

<!-- TARİHLER -->
<div class="w3-card w3-padding w3-margin-bottom">
  <div class="w3-row-padding">
    <div class="w3-half">
      Başlangıç
      <input class="w3-input w3-border" type="date" id="start">
    </div>
    <div class="w3-half">
      Sınav
      <input class="w3-input w3-border" type="date" id="end" value="2026-06-16">
    </div>
  </div>
</div>

<!-- KONTROLLER -->
<div class="w3-bar w3-margin-bottom no-print">
  <button class="w3-button w3-green" onclick="buildCalendar()">Takvimi Oluştur</button>
  <button class="w3-button w3-blue" onclick="smartDistribute()">Akıllı Dağıtım</button>
  <button class="w3-button w3-orange" onclick="applyExamResults()">Deneme Sonucuna Göre Gün Artır</button>
  <button class="w3-button w3-black" onclick="window.print()">PDF / Yazdır</button>
</div>

<!-- DERSLER -->
<?php foreach(DB::read("dersler") as $ders): ?>
<div class="w3-card w3-margin-bottom" data-ders="<?= $ders['ders_adi'] ?>">
  <div class="w3-padding w3-blue">
    <b><?= $ders["ders_adi"] ?></b>
  </div>

  <table class="w3-table w3-bordered">
    <tr>
      <th>Ünite</th>
      <th>Ağırlık (0–5)</th>
      <th>Gün</th>
      <th>Tür</th>
    </tr>

    <?php foreach(DB::read("uniteler",["ders_id"=>$ders["id"]]) as $u): ?>
    <tr>
      <td><?= $u["unite_adi"] ?></td>
      <td>
        <input type="number" min="0" max="5" value="1"
               class="w3-input w3-border agirlik">
      </td>
      <td>
        <select class="w3-select gun">
          <?php for($i=0;$i<=20;$i++):?><option><?= $i ?></option><?php endfor;?>
        </select>
      </td>
      <td>
        <select class="w3-select tur">
          <option value="calisma">Çalışma</option>
          <option value="tekrar">Tekrar</option>
          <option value="deneme">Deneme</option>
        </select>
      </td>
    </tr>
    <?php endforeach;?>
  </table>

  <!-- DERS PROGRESS -->
  <div class="w3-padding">
    <div class="w3-light-grey">
      <div class="w3-green ders-progress" style="height:18px;width:0%"></div>
    </div>
  </div>
</div>
<?php endforeach;?>

<!-- TAKVİM -->
<h3 class="w3-center">Takvim (Sürükle & Bırak)</h3>
<div id="calendar" class="w3-row-padding"></div>

</div>

<!-- STICKY GENEL BAR -->
<div class="w3-bottom w3-card w3-white w3-padding sticky-bar">
  <div class="w3-row">
    <div class="w3-third"><b>Toplam Gün:</b> <span id="totalDays">0</span></div>
    <div class="w3-third"><b>Atanan:</b> <span id="usedDays">0</span></div>
    <div class="w3-third"><b>Kalan:</b> <span id="leftDays">0</span></div>
  </div>
  <div class="w3-light-grey w3-margin-top">
    <div class="w3-blue" id="overallProgress" style="height:18px;width:0%"></div>
  </div>
</div>

<script>
let calendar=document.getElementById("calendar");

function daysBetween(){
  return (new Date(end.value)-new Date(start.value))/(1000*60*60*24)+1;
}

function buildCalendar(){
  calendar.innerHTML="";
  let d=new Date(start.value);
  for(let i=0;i<daysBetween();i++){
    let col=document.createElement("div");
    col.className="w3-col m2 w3-margin-bottom";
    col.innerHTML=`
      <div class="w3-card w3-white day"
           ondragover="event.preventDefault()"
           ondrop="drop(event)">
        <div class="w3-padding-small w3-light-grey"><b>${d.toLocaleDateString()}</b></div>
        <div class="w3-padding-small dropzone" data-day="${i}"></div>
      </div>`;
    calendar.appendChild(col);
    d.setDate(d.getDate()+1);
  }
  refreshStats();
}

function createUnitCard(ders,unite,tur){
  let div=document.createElement("div");
  div.className=`w3-tag w3-small w3-margin-bottom unit-card ${tur=='deneme'?'w3-red':tur=='tekrar'?'w3-orange':'w3-green'}`;
  div.draggable=true;
  div.ondragstart=e=>e.dataTransfer.setData("text",div.outerHTML);
  div.innerHTML=`${ders}<br>${unite}<br>${tur.toUpperCase()}`;
  return div;
}

function drop(e){
  e.preventDefault();
  let html=e.dataTransfer.getData("text");
  e.target.closest(".dropzone").insertAdjacentHTML("beforeend",html);
  refreshStats();
}

function smartDistribute(){
  document.querySelectorAll(".gun").forEach(g=>{
    let w=+g.closest("tr").querySelector(".agirlik").value;
    g.value=Math.min(10,w*2);
  });
  distribute();
}

function distribute(){
  document.querySelectorAll(".dropzone").forEach(z=>z.innerHTML="");
  let day=0;
  document.querySelectorAll("table tr").forEach(tr=>{
    let gun=tr.querySelector(".gun");
    if(!gun) return;
    let count=+gun.value;
    let ders=tr.closest("[data-ders]").dataset.ders;
    let unite=tr.children[0].innerText;
    let tur=tr.querySelector(".tur").value;
    for(let i=0;i<count;i++){
      let z=document.querySelector(`.dropzone[data-day='${day}']`);
      if(z){
        z.appendChild(createUnitCard(ders,unite,tur));
        day++;
      }
    }
  });
  refreshStats();
}

function applyExamResults(){
  let score=prompt("Son deneme yüzdesi (0-100):");
  if(score<50){
    document.querySelectorAll(".gun").forEach(g=>g.value=+g.value+1);
  }
  distribute();
}

function refreshStats(){
  let used=document.querySelectorAll(".unit-card").length;
  let total=daysBetween();
  document.getElementById("totalDays").innerText=total;
  document.getElementById("usedDays").innerText=used;
  document.getElementById("leftDays").innerText=Math.max(0,total-used);
  document.getElementById("overallProgress").style.width=(used/total*100||0)+"%";
}

document.querySelectorAll("select,input").forEach(e=>{
  e.addEventListener("change",distribute);
});
</script>

</body>
</html>
