<?php

$days_of_week = [
    ["name" => "Pazartesi", "short" => "PTS"],
    ["name" => "Salı",      "short" => "SAL"],
    ["name" => "Çarşamba",  "short" => "ÇAR"],
    ["name" => "Perşembe",  "short" => "PER"],
    ["name" => "Cuma",      "short" => "CUM"],
    ["name" => "Cumartesi", "short" => "CMT"],
    ["name" => "Pazar",     "short" => "PAZ"],
];
 

$lessons = [
    ["name" => "Türkçe",        "short" => "TRK"],
    ["name" => "Matematik",     "short" => "MAT"],
    ["name" => "Fen Bilimleri", "short" => "FEN"],
    ["name" => "İnkılap Tarihi","short" => "INK"],
    ["name" => "Din Kültürü",   "short" => "DIN"],
    ["name" => "İngilizce",     "short" => "ING"],
];

?>
<script defer>
    const btn_add_lessons=document.querySelectorAll('.btn_add_lesson');
    btn_add_lessons.forEach( (btn)=>{
        btn.addEventListener('click',()=>{
            console.log(btn);

        })
        
    })    
    function add_lesson(day,lesson,hour){
        if( !day || !lesson || !hour) return;

  }

</script>
 <div id="id01" class=" modal ">
  <div class="modal-content black border">
    <div class="container padding-small">
        <div class="row">
            <div class="col s6 center padding-small">
                <label for="day">Ders</label>
                <select name="day" id="day_name" class="input">
                    <?php foreach($lessons as $lesson): ?>
                        <option value="<?= $lesson["short"] ?>"><?= $lesson["name"] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col s2 center padding-small">
                Saat 
                <select name="hour_count" id="hour_count" class="input">
                        <?php for($i=1;$i<20;$i++):?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor?>
                </select>
            </div>
            <div class="col s4 center padding-small">
                <label for="" class="">İşlem</label>
                <button type="button" class="button block btn_add_lesson" data-day="<?= $day["short"] ?>" >Ekle</button>
            </div>
        </div>
    </div>
  </div>
</div>

<table class="table">
    <tr>
        <?php foreach($days_of_week as $day): ?>
            <th class="center">
                <?= htmlspecialchars($day["short"]) ?>
            </th>
        <?php endforeach; ?>
    </tr>

    <tr>
        <?php foreach($days_of_week as $day): ?>
            <td class="center">
                <div class="row" id="lesson_col"></div>
                <button type="button" class="button small border">+</button>
            </td>
        <?php endforeach; ?>
    </tr>
</table>