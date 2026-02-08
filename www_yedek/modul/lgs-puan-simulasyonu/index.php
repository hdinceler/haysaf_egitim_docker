<div class="container content center" style="max-width:900px">

  <!-- TITLE -->
  <div class="margin-bottom">
    <h2 class="xxlarge bold">
      LGS Puan Simülasyonu
    </h2>
    <div class="small opacity">
      Ders bazlı net, yüzde ve tahmini puan hesaplama
    </div>
  </div>

  <!-- HEADER (DESKTOP) -->
  <div class="row border-bottom padding hide-small bold">
    <div class="col s3">Ders</div>
    <div class="col s2 center">Doğru</div>
    <div class="col s2 center">Yanlış</div>
    <div class="col s2 center">Net</div>
    <div class="col s3 center">Durum</div>
  </div>

  <!-- DERSLER -->
  <div id="dersler" class="margin-top"></div>

  <!-- SUMMARY -->
  <div class="border padding margin-top center">

    <div id="genelNet" class="large bold margin-bottom"></div>

    <div id="genelYuzde" class="margin-bottom"></div>

    <div id="genelPuan" class="xxlarge bold margin-bottom"></div>

    <div id="genelMesaj" class="padding border bold"></div>

  </div>

</div>

<script>
const dersler=[
 {id:"turkce",ad:"Türkçe",s:20,k:4},
 {id:"mat",ad:"Matematik",s:20,k:4},
 {id:"fen",ad:"Fen",s:20,k:4},
 {id:"ink",ad:"İnkılap",s:10,k:1},
 {id:"din",ad:"Din",s:10,k:1},
 {id:"ing",ad:"İngilizce",s:10,k:1},
];

const alan=document.getElementById("dersler");

dersler.forEach(d=>{
  alan.innerHTML += `
<div class="row border-bottom padding center">

  <div class="col s12 m3 l3 left-align padding-small bold">
    ${d.ad}
  </div>

  <div class="col s6 m2 l2 padding-small">
    <select class="select border" id="${d.id}d">${opt(d.s)}</select>
  </div>

  <div class="col s6 m2 l2 padding-small">
    <select class="select border" id="${d.id}y">${opt(d.s)}</select>
  </div>

  <div class="col s6 m2 l2 padding-small">
    <div class="border padding bold" id="${d.id}n">0</div>
  </div>

  <div class="col s6 m3 l3 padding-small">

    <div class="border" style="height:16px">
      <div id="${d.id}b" style="height:100%;width:0%"></div>
    </div>

    <div class="small margin-top" id="${d.id}m"></div>

  </div>

</div>
`;

});

function opt(n){
  let o="";
  for(let i=0;i<=n;i++) o+=`<option>${i}</option>`;
  return o;
}

function durum(y){
  if(y<40)return["red","KRİTİK"];
  if(y<50)return["orange","Çalışmaya devam"];
  if(y<65)return["amber","Bol soru çözmelisin"];
  if(y<75)return["light-green","İyisin"];
  return["green","Harikasın"];
}

document.querySelectorAll("select").forEach(s=>{
  s.addEventListener("change",hesapla);
});

function hesapla(){
  let toplamNet=0, hamPuan=0, maxPuan=0;

  dersler.forEach(d=>{
    let dogru=+V(d.id+"d");
    let yanlis=+V(d.id+"y");

    if(dogru+yanlis>d.s){
      yanlis=d.s-dogru;
      S(d.id+"y",yanlis);
    }

    let net=Math.max(0,dogru-(yanlis/3));
    let yuzde=(net/d.s)*100;
    let [cls,msg]=durum(yuzde);

    T(d.id+"n",net.toFixed(2));
    document.getElementById(d.id+"n").className="tag "+cls;

    T(d.id+"m",msg);
    document.getElementById(d.id+"b").className=cls;
    W(d.id+"b",yuzde+"%");

    toplamNet+=net;
    hamPuan+=net*d.k;
    maxPuan+=d.s*d.k;
  });

  let puan=100+(hamPuan/maxPuan)*400;
  if(puan>500) puan=500;

  let genelYuzde=(toplamNet/90)*100;
  let [gcls,gmsg]=durum(genelYuzde);

  T("genelNet","Net: "+toplamNet.toFixed(2));
  T("genelYuzde","% "+genelYuzde.toFixed(1));
  T("genelPuan","Puan: "+puan.toFixed(1));
  T("genelMesaj",gmsg);
  document.getElementById("genelMesaj").className="tag "+gcls;

  localStorage.setItem("lgs",JSON.stringify(
    dersler.reduce((a,d)=>{
      a[d.id]={d:V(d.id+"d"),y:V(d.id+"y")};
      return a;
    },{})
  ));
}

function load(){
  const data=JSON.parse(localStorage.getItem("lgs")||"{}");
  dersler.forEach(d=>{
    if(data[d.id]){
      S(d.id+"d",data[d.id].d);
      S(d.id+"y",data[d.id].y);
    }
  });
  hesapla();
}

const V=i=>document.getElementById(i).value;
const S=(i,v)=>document.getElementById(i).value=v;
const T=(i,t)=>document.getElementById(i).innerText=t;
const W=(i,w)=>document.getElementById(i).style.width=w;

load();
</script>
