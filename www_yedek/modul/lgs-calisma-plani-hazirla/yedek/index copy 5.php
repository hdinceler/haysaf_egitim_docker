<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>LGS Çalışma Programı</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<style>
.day-card{min-height:160px; position:relative}
.plus{
  font-size:48px;
  cursor:pointer;
  color:#555;
}
.plus:hover{color:#000}
.sticky-bar{
  background:#111;
  color:#fff;
  box-shadow:0 -6px 20px rgba(0,0,0,.6);
}
</style>
</head>

<body class="w3-light-grey">

<div class="w3-container w3-margin">

<h2 class="w3-center">LGS Günlük Saat Bazlı Plan</h2>

<!-- TARİHLER -->
<div class="w3-card w3-padding w3-margin-bottom">
  <div class="w3-row-padding">
    <div class="w3-half">
      Başlangıç
      <input type="date" id="start" class="w3-input w3-border">
    </div>
    <div class="w3-half">
      Sınav
      <input type="date" id="end" class="w3-input w3-border" value="2026-06-16">
    </div>
  </div>
</div>

<div class="w3-bar w3-margin-bottom">
  <button class="w3-button w3-green" onclick="buildGrid()">Takvimi Oluştur</button>
  <button class="w3-button w3-black" onclick="window.print()">PDF / Yazdır</button>
</div>

<!-- TAKVİM -->
<div id="calendar" class="w3-row-padding"></div>

</div>

<!-- MODAL -->
<div id="modal" class="w3-modal">
  <div class="w3-modal-content w3-card w3-padding">
    <h3>Ders Ekle</h3>

    Ders
    <select id="dersSelect" class="w3-select w3-border" onchange="loadUnits()">
      <option value="">Seçiniz</option>
      <?php foreach(DB::read("dersler") as $d): ?>
        <option value="<?= $d['id'] ?>" data-ad="<?= $d['ders_adi'] ?>">
          <?= $d['ders_adi'] ?>
        </option>
      <?php endforeach;?>
    </select>

    Ünite
    <select id="uniteSelect" class="w3-select w3-border"></select>

    Saat
    <select id="hourSelect" class="w3-select w3-border">
      <?php for($i=1;$i<=6;$i++):?><option><?= $i ?></option><?php endfor;?>
    </select>

    <div class="w3-margin-top">
      <button class="w3-button w3-green" onclick="addLesson()">Ekle</button>
      <button class="w3-button w3-red" onclick="closeModal()">İptal</button>
    </div>
  </div>
</div>

<!-- STICKY BAR -->
<div class="w3-bottom w3-padding sticky-bar">
  <div class="w3-row w3-large">
    <div class="w3-third">Toplam Saat: <b id="totalH">0</b></div>
    <div class="w3-third">Planlanan: <b id="usedH">0</b></div>
    <div class="w3-third">Kalan: <b id="leftH">0</b></div>
  </div>
  <div class="w3-dark-grey">
    <div class="w3-green" id="overallBar" style="height:22px;width:0%"></div>
  </div>
</div>

<script>
let activeDay=null;
let dayHours={};

function buildGrid(){
  let cal=document.getElementById("calendar");
  cal.innerHTML="";
  dayHours={};

  let d=new Date(start.value);
  let endD=new Date(end.value);

  while(d<=endD){
    let key=d.toISOString().split("T")[0];
    dayHours[key]=0;

    let col=document.createElement("div");
    col.className="w3-col m2 w3-margin-bottom";
    col.innerHTML=`
      <div class="w3-card w3-white day-card">
        <div class="w3-center w3-padding-small w3-light-grey">
          <b>${d.toLocaleDateString()}</b>
        </div>

        <div class="w3-center plus"
             onclick="openModal('${key}')">+</div>

        <div class="w3-padding" id="list-${key}"></div>

        <div class="w3-light-grey">
          <div id="bar-${key}" class="w3-blue" style="height:10px;width:0%"></div>
        </div>
      </div>`;
    cal.appendChild(col);
    d.setDate(d.getDate()+1);
  }
  refreshTotals();
}

function openModal(day){
  activeDay=day;
  document.getElementById("modal").style.display="block";
}

function closeModal(){
  document.getElementById("modal").style.display="none";
}

function loadUnits(){
  let dersId=dersSelect.value;
  uniteSelect.innerHTML="";
  <?php
  $map=[];
  foreach(DB::read("uniteler") as $u){
    $map[$u["ders_id"]][]=$u["unite_adi"];
  }
  echo "const units=".json_encode($map).";";
  ?>
  if(units[dersId]){
    units[dersId].forEach(u=>{
      uniteSelect.innerHTML+=`<option>${u}</option>`;
    });
  }
}

function addLesson(){
  let h=+hourSelect.value;
  if(dayHours[activeDay]+h>6){
    alert("Bir günde maksimum 6 saat!");
    return;
  }

  let ders=dersSelect.selectedOptions[0].text;
  let unite=uniteSelect.value;

  document.getElementById("list-"+activeDay).innerHTML+=`
    <div class="w3-small w3-tag w3-green w3-margin-bottom">
      ${ders}<br>${unite} – ${h} saat
    </div>`;

  dayHours[activeDay]+=h;
  document.getElementById("bar-"+activeDay).style.width=
    (dayHours[activeDay]/6*100)+"%";

  closeModal();
  refreshTotals();
}

function refreshTotals(){
  let days=Object.keys(dayHours).length;
  let used=Object.values(dayHours).reduce((a,b)=>a+b,0);
  let total=days*6;

  totalH.innerText=total;
  usedH.innerText=used;
  leftH.innerText=Math.max(0,total-used);
  overallBar.style.width=(used/total*100||0)+"%";
}
</script>

</body>
</html>
