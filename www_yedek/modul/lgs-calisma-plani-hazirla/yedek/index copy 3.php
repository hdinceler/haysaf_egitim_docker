 <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<div class="w3-container w3-margin">

<h2 class="w3-center">LGS Çalışma Programı</h2>

<div class="w3-row w3-card w3-padding w3-margin-bottom">
  <div class="w3-half">
    Başlangıç Tarihi
    <input class="w3-input w3-border" type="date" id="start">
  </div>
  <div class="w3-half">
    Sınav Tarihi
    <input class="w3-input w3-border" type="date" id="end" value="2026-06-16">
  </div>
</div>

<?php foreach(DB::read("dersler") as $ders): ?>
<div class="w3-card w3-margin-bottom">
  <div class="w3-padding w3-blue">
    <b><?= $ders["ders_adi"] ?></b>
  </div>

  <table class="w3-table w3-bordered">
    <tr>
      <th>Ünite</th>
      <th>Gün</th>
      <th>Tür</th>
    </tr>

    <?php foreach(DB::read("uniteler",["ders_id"=>$ders["id"]]) as $u): ?>
    <tr>
      <td><?= $u["unite_adi"] ?></td>
      <td>
        <select class="w3-select gunSec"
          data-ders="<?= $ders["ders_adi"] ?>"
          data-unite="<?= $u["unite_adi"] ?>">
          <?php for($i=0;$i<=20;$i++): ?>
            <option><?= $i ?></option>
          <?php endfor;?>
        </select>
      </td>
      <td>
        <select class="w3-select turSec">
          <option value="calisma">Çalışma</option>
          <option value="deneme">Deneme</option>
          <option value="tekrar">Tekrar</option>
        </select>
      </td>
    </tr>
    <?php endforeach;?>
  </table>

  <div class="w3-padding">
    <div class="w3-light-grey">
      <div class="w3-green dersBar" style="height:20px;width:0%"></div>
    </div>
  </div>
</div>
<?php endforeach;?>

<h3 class="w3-center">Takvim</h3>
<div id="takvim" class="w3-row-padding"></div>

</div>

<!-- STICKY ALT PANEL -->
<div class="w3-bottom w3-card w3-white w3-padding">
  <b>Toplam Gün:</b> <span id="toplamGun">0</span> |
  <b>Atanan:</b> <span id="atananGun">0</span> |
  <b>Kalan:</b> <span id="kalanGun">0</span>
</div>

<script>
const takvim = document.getElementById("takvim");
const start = document.getElementById("start");
const end = document.getElementById("end");

function gunSayisi(){
  return (new Date(end.value)-new Date(start.value))/(1000*60*60*24)+1;
}

function takvimOlustur(){
  takvim.innerHTML="";
  let d = new Date(start.value);
  for(let i=0;i<gunSayisi();i++){
    let box = document.createElement("div");
    box.className="w3-col m2 w3-border w3-padding w3-small";
    box.dataset.gun=i;
    box.innerHTML="<b>"+d.toLocaleDateString()+"</b><div class='icerik'></div>";
    takvim.appendChild(box);
    d.setDate(d.getDate()+1);
  }
}

function dagit(){
  document.querySelectorAll(".icerik").forEach(i=>i.innerHTML="");

  let gunIndex=0, atanan=0;

  document.querySelectorAll(".gunSec").forEach((s)=>{
    let gun = +s.value;
    let ders = s.dataset.ders;
    let unite = s.dataset.unite;
    let tur = s.parentElement.nextElementSibling.querySelector(".turSec").value;

    for(let i=0;i<gun;i++){
      let hedef = document.querySelector(`[data-gun='${gunIndex}'] .icerik`);
      if(hedef){
        hedef.innerHTML += `<div class="w3-tag w3-small w3-${tur=='deneme'?'red':tur=='tekrar'?'orange':'green'} w3-margin-bottom">
          ${ders}<br>${unite}<br>${tur.toUpperCase()}
        </div>`;
        gunIndex++;
        atanan++;
      }
    }
  });

  document.getElementById("toplamGun").innerText = gunSayisi();
  document.getElementById("atananGun").innerText = atanan;
  document.getElementById("kalanGun").innerText = gunSayisi()-atanan;
}

document.querySelectorAll("select,input").forEach(e=>{
  e.addEventListener("change",()=>{
    if(start.value && end.value){
      takvimOlustur();
      dagit();
    }
  });
});
</script>
