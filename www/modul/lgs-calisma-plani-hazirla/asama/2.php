<?php

// debug('session', istisna_havuz_getir());
?>

<div class="padding-small">

    <h4 class="center text-indigo margin-bottom">İstisnâ gün ekle
        <br> <small>Bu günlerde ders çalışılmayacaktır</small></h4>
    <form action="/lgs-calisma-plani-hazirla?asama=2" method="post">
        <input type="hidden" name="istisna_gun_ekle">
        <div class="row margin-top">
            <div class="col s3 m3 l3 padding-small">
                    <label>Tarih</label>
                    <input type="date" class="input border" name="tarih" value="<?= $_SESSION["plan"]["tarih_baslangic"]??''?>">
            </div>
            <div class="col s6 m6 l6 padding-small">
                    <label>İsim</label>
                    <input type="text" class="input border" name="isim" value="">
            </div>
            <div class="col s3 m3 l3 padding-small">
                <br/>
                <input type="submit"  class="button block border center" value="Listeye Ekle"></input>
            </div>
    </div>
    </form>

</div>
<h4>Öntanımlı İstisna Günler</h4>
<div class="row">
    <div class="col s10 padding-small">
        <select name="" id="" class="select">
            <?php foreach( $_SESSION["plan"]["istisna"]["db"] as $tarih=>$istisna_db ):?>
                <option value=" <?= PLAN::tarih_tr($tarih) ?>"><?= PLAN::tarih_tr($tarih) ?> <?= $istisna_db ?></option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col s2 padding-small">
            <input type="button" value="Ekle" class="btn button green block">
        </div>
</div>





<table class="table table-all text-black">
    <thead>
        <th>Tarih</th>
        <th>İsim</th>
        <th>İşlem</th>
    </thead>
    <tbody>
    <?php foreach( $_SESSION["plan"]["istisna"]["db"] as $tarih=>$istisna_db ):?>
        <tr class="padding-small">
            <td class="border">
                <?= PLAN::tarih_tr($tarih) ?>
            </td>
            <td class="border">
                <?= $istisna_db ?>
            </td>
            <td class="border">
                <form action="" method="post">
                    <input type="hidden" name="istisna_sil" value="<?= $tarih ?>">
                    <input type="submit" value="Sil" class="border border-red text-red btn small block">
                </form>
            </td>
        </tr>


    <?php endforeach;?>
    </tbody>
</table>
<div class="row padding-small">
    <button class="block btn border margin-top button">Kaydet</button>
</div>


<?php

 ?>


<form method="post" action="">
    <?php if (empty($istisna_gunler)): ?>

    <div class="center">
        Seçilen tarih aralığında istisna gün bulunamadı.
    </div>

<?php else: ?>

    <label for="istisna_select"><strong>Öntanımlı İstisna Günler:</strong></label>

    <select
        name="istisna_ids[]"
        id="istisna_select"
        multiple
        size="10"
        class=" block striped "

    >

        <?php foreach ($istisna_gunler as $i => $istisna): ?>

            <?php
                // Tarihi hızlı formatla
                $tarih_tr = implode('.', array_reverse(explode('-', $istisna["tarih"])));
            ?>

            <option value="<?= $istisna["tarih"] ?>">
                <?= htmlspecialchars($tarih_tr . ' - ' . $istisna["isim"]) ?>
            </option>

        <?php endforeach; ?>

    </select>

<?php endif; ?>


    <div class="margin-top">
        <button type="submit" name="istisna_ekle" class="button block border center">
         Seçili İstisna Günleri Ekle
        </button>
    </div>
</form>