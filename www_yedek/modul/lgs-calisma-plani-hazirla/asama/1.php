<?php
//  daha önceden geçerli bir tarih ataması var mı?
    
// tarih aralığı post edildiyse :
     // başlangıç ve bitiş belirtilmiş mi
     //başlangıç ve bitiş formata uygun mu
     //başlangıç tarihi bitiş tarihinden küçük mü
     // tarihileri sessiona yaz ve sonraki aşamaları temizle (plan yeniden oluşturulacak)
// tarih başlangıç ve bitiş seçili mi


?>


<div class=" ">

    <h4 class="center text-indigo margin-bottom">1. ADIM: Tarih aralığını Belirle</h4>

    <form action="/lgs-calisma-plani-hazirla?asama=1" method="post">
        <input type="hidden" name="tarih_araligi_belirle">
    <div class="row-padding margin-top">
        <div class="col s6">

                <label>Başlangıç Tarihi</label>
                <input type="date" class="input border black" name="tarih_baslangic" value="<?= $_SESSION["plan"]["tarih_baslangic"]??date('Y-m-d')?>">
            </div>
            <div class="col s6">
                <label>Bitiş Tarihi</label>
               <input type="date" class="input border black" name="tarih_bitis"
       value="<?= htmlspecialchars($_SESSION["plan"]["tarih_bitis"] ??'', ENT_QUOTES, 'UTF-8') ?>">

                <input type="hidden" name="tarih_belirle">
            </div>
        </div>
        
            <div class="row center padding">
                <input type="submit"  class="button block border center" value="Tarih Belirle"></input>
            </div>
    </form>

    <form action="" method="post">
        <input type="hidden" name="sifirla">
        <input type="submit" value="Sıfırla" class="block border btn">
    </form>

 
</div>
