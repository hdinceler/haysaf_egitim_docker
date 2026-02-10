<?php 
$butonlar=[
     ["ad"=>"tarih","text"=> "Tarih" , 'status'=>PLAN::tarih_araligi_dogru_girilmis()],
     ["ad"=>"istisna","text"=> "İstisna"],
     ["ad"=>"gun","text"=> "Gün"],
     ["ad"=>"ders","text"=> "Ders"],
     ["ad"=>"unite","text"=> "Ünite"],
     ["ad"=>"rapor","text"=> "Rapor"],
    ];
$col= round( 12 / count($butonlar)  );

?>
<div class="row">
    <?php foreach( $butonlar as $key=>$btn) :
        $durum=$btn['status']??'';
        $btn_class= ($durum!== true)? 'border-red' : 'border-green'; 
    ?>
        <div class="col s<?= $col ?>">
            <div class="<?= $btn_class ?> border">
                <a href="?asama=<?= $key+1 ?>" class="btn block ">
                    <span class="block large"> <?= $key+1?> </span>
                    <?= $btn["text"] ?> 
                </a>
            </div>
        </div>
    <?php endforeach?>
</div>

<div class="border padding-small margin-top margin-bottom">
    <?php foreach($mesajlar["plan"] as $mesaj):?>
        <div class="row">
            <?=  PLAN::ico($mesaj['type']) ?>
            <strong><?= $mesaj["title"] ?>:</strong> 
            <strong><?= $mesaj["text"] ?></strong> 
        </div>
    <?php endforeach;?>
   
    <form action="" method="post">
        <input type="hidden" name="sifirla">
        <input type="submit" value="Sıfırla" class=" button ">
    </form>
</div>

 


 
 