<style>
table{border-collapse:collapse}
th,td{border:1px solid #ccc}
.lesson{
  background:#f4f8ff;
  border:1px solid #bbb;
  padding:4px;
  margin-bottom:4px;
}
@media print{
  .no-print{display:none}
  body{background:#fff}
}
.sticky-bar{
  background:#000;
  color:#fff;
  box-shadow:0 -4px 12px rgba(0,0,0,.6);
}
</style>
 
<div class="container margin">

<h2 class="center">LGS Günlük Çalışma Planı</h2>

<!-- TARİHLER -->
<div class="card padding margin-bottom">
  <div class="row-padding">
    <div class="half">
      Başlangıç
      <input type="date" id="start" class="input">
    </div>
    <div class="half">
      Sınav
      <input type="date" id="end" class="input" value="2026-06-16">
    </div>
  </div>
</div>

<!-- GÜNLÜK MAKS SAAT -->
<div class="card padding margin-bottom">
  <h4>Haftalık Maksimum Çalışma Saatleri</h4>
  <div class="row-padding">
    <script>
      const daysTR=["Pazar","Pzt","Salı","Çrş","Per","Cum","Cts"];
    </script>
    <?php for($i=0;$i<7;$i++): ?>
    <div class="col m1">
      <?= ["Paz","Pzt","Sal","Çar","Per","Cum","Cts"][$i] ?>
      <input type="number" min="1" max="10" value="6"
             class="input maxDay" data-day="<?= $i ?>">
    </div>
    <?php endfor;?>
  </div>
</div>

<div class="no-print margin-bottom">
  <button class="button green" onclick="buildTable()">Takvimi Oluştur</button>
  <button class="button black" onclick="window.print()">PDF / Yazdır</button>
</div>

<!-- TAKVİM TABLO -->
<table class="table white" id="planTable">
  <thead>
    <tr class="light-grey">
      <th style="width:140px">Tarih</th>
      <th>Plan</th>
      <th style="width:160px">İlerleme</th>
      <th style="width:60px">Ders Ekle</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

</div>

<!-- MODAL -->
<div id="modal" class="modal">
  <div class="modal-content padding card">
    <h3>Ders Ekle</h3>

    Ders
    <select id="dersSelect" class="select">
      <?php foreach(DB::read("dersler") as $d): ?>
        <option><?= $d["ders_adi"] ?></option>
      <?php endforeach;?>
    </select>   

    Ünite
    <input id="uniteInput" class="input">

    Saat
    <select id="hourSelect" class="select">
      <?php for($i=1;$i<=6;$i++):?><option><?= $i ?></option><?php endfor;?>
    </select>

    <div class="margin-top">
      <button class="button green" onclick="saveLesson()">Ekle</button>
      <button class="button red" onclick="closeModal()">İptal</button>
    </div>
  </div>
</div>

<!-- STICKY -->
<div class="bottom padding sticky-bar">
  Toplam: <b id="totalH">0</b> |
  Planlanan: <b id="usedH">0</b> |
  Kalan: <b id="leftH">0</b>
  <div class="dark-grey">
    <div id="overallBar" class="green" style="height:18px;width:0%"></div>
  </div>
</div>

<script>
let activeRow=null;
let dayUsage={};

function buildTable(){
  const body=document.querySelector("#planTable tbody");
  body.innerHTML="";
  dayUsage={};

  const today = new Date().toISOString().split('T')[0];
  start.value = today;
  let d=new Date(today), endD=new Date(end.value);

  while(d<=endD){
    let key=d.toISOString().split("T")[0];
    dayUsage[key]=0;

    body.innerHTML+=`
      <tr data-day="${key}" data-week="${d.getDay()}">
        <td>${d.toLocaleDateString()}</td>
        <td id="list-${key}"></td>
        <td>
          <div class="light-grey">
            <div id="bar-${key}" class="blue" style="height:14px;width:0%"></div>
          </div>
        </td>
        <td class="center">
          <button class="button small" onclick="openModal('${key}')">+</button>
        </td>
      </tr>`;
    d.setDate(d.getDate()+1);
  }
  refreshTotals();
}

function openModal(day){
  activeRow=day;
  modal.style.display="block";
}
function closeModal(){modal.style.display="none"}

function saveLesson(){
  let h=+hourSelect.value;
  let row=document.querySelector(`tr[data-day='${activeRow}']`);
  let max=+document.querySelector(
    `.maxDay[data-day='${row.dataset.week}']`).value;

  if(dayUsage[activeRow]+h>max){
    alert("Bu gün için maksimum saat aşıldı");
    return;
  }

  document.getElementById("list-"+activeRow).innerHTML+=`
    <div class="lesson">
      ${dersSelect.value} – ${uniteInput.value} (${h} saat)
    </div>`;

  dayUsage[activeRow]+=h;
  document.getElementById("bar-"+activeRow).style.width=
    (dayUsage[activeRow]/max*100)+"%";

  closeModal();
  refreshTotals();
}

function refreshTotals(){
  let used=Object.values(dayUsage).reduce((a,b)=>a+b,0);
  let max=0;
  document.querySelectorAll("tr[data-week]").forEach(r=>{
    max+=+document.querySelector(`.maxDay[data-day='${r.dataset.week}']`).value;
  });
  totalH.innerText=max;
  usedH.innerText=used;
  leftH.innerText=Math.max(0,max-used);
  overallBar.style.width=(used/max*100||0)+"%";
}
</script>
 