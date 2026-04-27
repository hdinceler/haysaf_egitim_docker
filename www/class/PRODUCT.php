<?php
class PRODUCT{

public static function bread_crumb_kategori($array){
    echo " <ul class='breadcrumb blue text-white'>";
    echo " <li><a href='/'>Giriş</a></li>";
    foreach($array as $bc){
        $sefLink = 'kategori/' . SEO::sefLink($bc["kategori_tr"]) . '/' . $bc["kategori_id"];
        echo " <li><a href='{$sefLink}'>{$bc["kategori_tr"]}</a></li>";
    }
    echo "</ul>";

}

public static function keywords_kategori($array){
    return implode(',', array_column($array,'kategori_tr'));
}


public static function sonsuz_kategori_listele($parent_id = null) {
    $urun_kategoriler = DB::callSP('urun_kategoriler', [$parent_id]);
    if (!$urun_kategoriler) return;
    
    echo "<div class='row'>";
    foreach ($urun_kategoriler as $kategori) {
        echo "<div class='col half padding-small'>";
        self::kategori_div_link($kategori['kategori_tr'], $kategori['kategori_id'], $parent_id);
        self::kategori_div_list($kategori['kategori_id']);
        echo "</div>";
    }
    echo "</div>";
}


public static function kategori_div_link($kategori_tr, $kategori_id, $parent_id = 0) {
    $sefLink = "kategori/" . SEO::sefLink($kategori_tr) . "/$kategori_id";
    echo <<<HTML
    <div class="col s12 m6 l4 padding-small">
        <a href="$sefLink">
            <div class="border padding-small hover-green border-black large">
                {$kategori_tr} 
            </div>
        </a>
    </div>
    HTML;
}

public static function  kategori_div_list($parent_id=null){
    $kats = DB::callSP('urun_kategoriler', [$parent_id]);
    // var_dump($kats);
    if(!$kats) return null;
    echo "<div class='row '>";
        foreach($kats  as $kat ):
            self::kategori_div_link($kat["kategori_tr"],$kat["kategori_id"],$kat["parent_id"]);
        endforeach;
    echo "</div>";
}

public static function kategori_tablo_satirla($parent_id=null){
    $kategoriler = DB::callSP('urun_kategoriler', [$parent_id]);
    if(!$kategoriler) return null;
    foreach($kategoriler as $kategori):
        $sefLink = 'kategori/' . SEO::sefLink($kategori["kategori_tr"]) . '/'. $kategori["kategori_id"];
        echo <<<HTML
        <tr>
            <td class=""> 
                <a href="$sefLink" class="large button block left-align"> {$kategori["kategori_tr"]}  
                <small>(parent_id:{$kategori["parent_id"]}, kategori_id:{$kategori["kategori_id"]})</small>
                </a>
            </td>
            <td> 12.543 </td>
        </tr>
        HTML;

        // self::kategori_tablo_satirla($kategori["kategori_id"]);
    endforeach;
}
    public static function kategori_tablola($parent_id=null){
        echo "
        <table class='table-all hoverable dropDown'>
            <thead class='dropDownBtn'>
                <th>Kategori</th>
                <th>Adet</th>
            </thead>
            <tbody class='dropContent'>
        ";
        self::kategori_tablo_satirla($parent_id);
        echo "</tbody></table>";
    }

   
public static function secili_kategori_dizisi($kategori_id){
    $array = [];
    $current_id = $kategori_id;
    // Üst kategorilere kadar çık
    while($current_id !== null){
        $sql = "SELECT kategori_id, parent_id, kategori_tr FROM urun_kategoriler WHERE kategori_id = :kategori_id";
        $data = DB::readRaw($sql, ["kategori_id" => $current_id]);
        if(!$data) break; // kategori yoksa çık
        $data = $data[0];
        $array[] = [
            "kategori_id" => $data["kategori_id"],
            "parent_id" => $data["parent_id"],
            "kategori_tr" => $data["kategori_tr"]
        ];
        $current_id = $data["parent_id"]; // bir üst kategoriye geç
    }
    $array = array_reverse($array);
    // var_dump($array);
    return $array;
} 


// verilen kategor_id si ve onun tüm alt kategorilerindeki tüm bileşenleri getirir.
public static function tum_alt_bilesenleri_getir($kategori_id){
    $urun_bilesenler = DB::callSP("urun_tum_alt_kategorilerdeki_bilesenler",["kategori_id"=>$kategori_id]);
    // var_dump($urun_bilesenler);
    return $urun_bilesenler ?:null;
}

public static function  bilesenleri_listele($array){
    if($array){
        // var_dump($array);
        echo "<div class=''>";
            foreach($array as $urun_bilesen):
                self::bilesen_linkle($urun_bilesen["bilesen_tr"], $urun_bilesen["bilesen_id"], $urun_bilesen["bilesen_id"]);
            endforeach;
        echo "</div>";
    }
}

public static function bilesen_fiyat($bilesen_id){
    $sql="SELECT * FROM urun_fiyatlar  WHERE bilesen_id=:bilesen_id ORDER BY zaman DESC";
    $fiyatlar=DB::readRaw($sql,["bilesen_id"=>$bilesen_id]);
    if(!$fiyatlar) return '? ₺';
    return $fiyatlar[0]["satis_fiyati"] ." ". $fiyatlar[0]["para_birimi"];
}
public static function bilesen_linkle($bilesen_tr, $kategori_id,$bilesen_id){
    $sefLink = 'urun/' . SEO::sefLink($bilesen_tr) . '/'. $kategori_id;
    $imgLink="/public/img/thumb/product/12.png";
    $fiyat= $f=self::bilesen_fiyat($bilesen_id) ;
    echo <<<HTML
        <div class="col s6 m4 l4 padding-small">
           
                <div class="row display-container border border-blue padding-small">
                    <div class="col s3">
                        <img src="$imgLink" class="image">
                    </div>
                    <div class="col s9 left-align large display-container">
                        <div class="row center"> <a href="$sefLink" class="padding-small block ">$bilesen_tr </a></div> 
                        <div class="row center text-blue xlarge">{$fiyat}</div>
                        <div class="row center small">
                            <div class="col third"> 
                                <a href="" class="hover-blue padding-small block">Sepet</a>
                            </div>
                            <div class="col third"> 
                                <a href="" class="hover-blue padding-small block">Favori</a>
                            </div>
                            <div class="col third"> 
                                <a href="" class="hover-blue padding-small block">Talep</a>
                            </div>
                        </div>
                    </div>
                </div>
           
        </div>
    HTML;



}

public static function urun_getir($urun_id){
    
}
     
}

 