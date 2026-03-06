<?php
date_default_timezone_set('Europe/Istanbul');

function calismaTakvimiGetir(string $baslangic, string $bitis): array
{
    $b = new DateTime($baslangic);
    $e = new DateTime($bitis);

    $aylar = [
        1=>'Ocak',2=>'Şubat',3=>'Mart',4=>'Nisan',5=>'Mayıs',6=>'Haziran',
        7=>'Temmuz',8=>'Ağustos',9=>'Eylül',10=>'Ekim',11=>'Kasım',12=>'Aralık'
    ];

    $gunler = [
        'Mon'=>'Pazartesi','Tue'=>'Salı','Wed'=>'Çarşamba',
        'Thu'=>'Perşembe','Fri'=>'Cuma','Sat'=>'Cumartesi','Sun'=>'Pazar'
    ];

    $takvim = [];
    while ($b <= $e) {
        $key = $aylar[(int)$b->format('n')] . ' ' . $b->format('Y');
        $takvim[$key][] = [
            'tarih'=>$b->format('Y-m-d'),
            'gunNo'=>$b->format('j'),
            'gun'=>$gunler[$b->format('D')]
        ];
        $b->modify('+1 day');
    }
    return $takvim;
}

$takvim = calismaTakvimiGetir('2025-12-15','2027-01-31');
?>
<!doctype html>
<html lang="tr">
<head>
<meta charset="utf-8">
<title>LGS 2026 Çalışma Takvimi</title>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<style>
@media print {
  .no-print { display:none }
  body { font-size:11px }
}
.unit-row{display:flex;gap:6px;align-items:center}
</style>
</head>

<body class="w3-content" style="max-width:1300px">

<div class="w3-bar w3-margin no-print">
  <button class="w3-button w3-border" onclick="window.print()">📄 PDF / Yazdır</button>
</div>

<h2 class="w3-center">LGS 2026 Çalışma Takvimi</h2>

<script>
/* === DERSLER (JSON) === */
const derslerJSON = [
 {id:1, ad:"Türkçe"},
 {id:2, ad:"Matematik"},
 {id:3, ad:"Fen Bilimleri"},
 {id:4, ad:"T.C. İnkılap Tarihi"},
 {id:5, ad:"Din Kültürü"},
 {id:6, ad:"İngilizce"}
];

/* === ÜNİTELER (JSON) === */
const unitelerJSON = {
 1:["Sözcükte Anlam","Cümlede Anlam","Paragraf","Fiilimsiler"],
 2:["Doğrusal Denklemler","Üslü Sayılar","Kareköklü İfadeler"],
 3:["DNA ve Genetik","Basınç","Asitler ve Bazlar"],
 4:["İnkılaplar","Atatürkçülük"],
 5:["Kader İnancı","Zekat"],
 6:["Friendship","Teen Life"]
};
</script>

<?php foreach($takvim as $ayYil=>$gunler): ?>
<h3 class="w3-border-bottom"><?= $ayYil ?></h3>

<div class="w3-responsive">
<table class="w3-table w3-bordered w3-small">
<thead class="w3-light-grey">
<tr>
 <th style="width:150px">Tarih</th>
 <th>Ders / Ünite</th>
 <th class="no-print" style="width:220px">İşlem</th>
</tr>
</thead>

<tbody>
<?php foreach($gunler as $g): ?>
<tr>
<td>
 <strong><?= $g['gunNo'] ?> <?= $ayYil ?></strong><br>
 <small><?= $g['gun'] ?></small>
</td>

<td>
 <ul class="w3-ul w3-small" id="liste-<?= $g['tarih'] ?>"></ul>
</td>

<td class="no-print">
 <select class="w3-select w3-small"
         id="ders-<?= $g['tarih'] ?>"
         onchange="uniteDoldur('<?= $g['tarih'] ?>')">
   <option value="">Ders</option>
 </select>

 <select class="w3-select w3-small w3-margin-top"
         id="unite-<?= $g['tarih'] ?>">
   <option value="">Ünite</option>
 </select>

 <button class="w3-button w3-small w3-border w3-margin-top"
         onclick="dersEkle('<?= $g['tarih'] ?>')">Ekle</button>

 <button class="w3-button w3-small w3-border w3-margin-top"
         onclick="seciliSil('<?= $g['tarih'] ?>')">Seçili Sil</button>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php endforeach; ?>

<script>
/* Ders dropdown doldur */
document.querySelectorAll('[id^="ders-"]').forEach(sel=>{
 derslerJSON.forEach(d=>{
   sel.add(new Option(d.ad,d.id));
 });
});

/* Ünite filtrele */
function uniteDoldur(tarih){
 const dID=document.getElementById('ders-'+tarih).value;
 const uSel=document.getElementById('unite-'+tarih);
 uSel.innerHTML='<option value="">Ünite</option>';
 if(unitelerJSON[dID]){
   unitelerJSON[dID].forEach(u=>{
     uSel.add(new Option(u,u));
   });
 }
}

/* Ders + ünite ekle */
function dersEkle(tarih){
 const dSel=document.getElementById('ders-'+tarih);
 const uSel=document.getElementById('unite-'+tarih);
 if(!dSel.value||!uSel.value) return alert("Ders ve ünite seçin");

 const ul=document.getElementById('liste-'+tarih);
 const li=document.createElement('li');
 li.className='unit-row';
 li.innerHTML=`<input type="checkbox">
               <strong>${dSel.options[dSel.selectedIndex].text}</strong>
               <small>(${uSel.value})</small>`;
 ul.appendChild(li);
}

/* Seçili sil */
function seciliSil(tarih){
 document.querySelectorAll('#liste-'+tarih+' input:checked')
   .forEach(c=>c.closest('li').remove());
}
</script>

</body>
</html>
