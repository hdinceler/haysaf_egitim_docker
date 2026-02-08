<h2 class="center margin-bottom">
  LGS Çalışma Programı Hazırla
</h2>

<!-- TARİHLER -->
<div class="card padding margin-bottom">

  <div class="row-padding">
    <div class="half">
      <label>Başlangıç Tarihi</label>
      <input class="input border"
             type="date"
             id="baslangic">
    </div>

    <div class="half">
      <label>Sınav Tarihi</label>
      <input class="input border"
             type="date"
             id="bitis"
             value="2026-06-16">
    </div>
  </div>

</div>

<?php foreach(DB::read("dersler") as $ders): ?>
<div class="card margin-bottom padding">

  <!-- DERS BAŞLIĞI -->
  <h3 class="text-blue">
    <?= $ders["ders_adi"] ?>
    <span class="small opacity">
      (<?= $ders["toplam_soru"] ?> soru)
    </span>
  </h3>

  <!-- DERS PROGRESS -->
  <div class="light-grey round" style="height:12px">
    <div id="bar-<?= $ders["id"] ?>"
         class="green round"
         style="height:100%;width:0%">
    </div>
  </div>
  <div class="small margin-bottom">
    Kalan Gün: <span id="kalan-<?= $ders["id"] ?>"></span>
  </div>

  <!-- ÜNİTELER -->
  <div class="responsive">
    <table class="table bordered small">

      <thead class="light-grey">
        <tr>
          <th>Ünite</th>
          <th>2022</th>
          <th>2023</th>
          <th>2024</th>
          <th>2025</th>
          <th>Gün</th>
        </tr>
      </thead>

      <tbody>
      <?php foreach(DB::read("uniteler",["ders_id"=>$ders["id"]]) as $u): ?>
        <tr>
          <td><?= $u["unite_adi"] ?></td>
          <td><?= $u["soru_2022"] ?></td>
          <td><?= $u["soru_2023"] ?></td>
          <td><?= $u["soru_2024"] ?></td>
          <td><?= $u["soru_2025"] ?></td>

          <!-- GÜN SEÇİMİ -->
          <td>
            <select class="select gun-sec"
                    data-ders="<?= $ders["id"] ?>">
              <?php for($i=0;$i<=30;$i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
              <?php endfor; ?>
            </select>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>

    </table>
  </div>

</div>
<?php endforeach; ?>
<script>
function gunFarki(a,b){
  return Math.ceil((b-a)/(1000*60*60*24));
}

function hesapla(){
  const bas = new Date(baslangic.value).getTime();
  const bit = new Date(bitis.value).getTime();
  if(!bas || !bit || bas>=bit) return;

  const toplamGun = gunFarki(bas,bit);

  const dersler = {};

  document.querySelectorAll(".gun-sec").forEach(s=>{
    const id = s.dataset.ders;
    dersler[id] = (dersler[id]||0) + Number(s.value);
  });

  Object.keys(dersler).forEach(id=>{
    const verilen = dersler[id];
    const kalan   = Math.max(0, toplamGun - verilen);
    const yuzde   = Math.min(100, (verilen/toplamGun)*100);

    document.getElementById("kalan-"+id).innerText = kalan;
    document.getElementById("bar-"+id).style.width = yuzde+"%";
    document.getElementById("bar-"+id).className =
      "round " + (kalan===0 ? "green" : "orange");
  });
}

document.querySelectorAll("input,select")
  .forEach(e=>e.addEventListener("change",hesapla));
</script>
