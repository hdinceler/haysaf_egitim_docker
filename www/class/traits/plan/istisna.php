<?php
trait istisna{

    /* ========== DOĞRUDAN KULLANILANLAR ========== */

    public static function istisnalar_db_getir(string $tarih_baslangic,string  $tarih_bitis):array{
        self::autoInit();
        $rows=DB::readRaw(
            "SELECT tarih, isim from tatiller WHERE tarih BETWEEN :baslangic AND :bitis ORDER BY tarih ASC",
            ["baslangic"=>$tarih_baslangic,"bitis"=> $tarih_bitis]
        );
        $_SESSION['plan']['istisna']['db']=is_array( $rows ) ? array_column( $rows,'isim','tarih'): [];
        return $_SESSION["plan"]["istisna"]["db"];
    }

    public static function istisnalar_havuza_ekle(array $istisnalar): void
    {
        self::autoInit();
        $yeni=array_replace(
            self::istisnalar_havuz_getir(),
            $istisnalar
            );
        $_SESSION['plan']['istisna']['havuz']=$yeni;
        $_SESSION['plan']['istisna']['istisna_gunler']=count($yeni);

    }

    public static function istisnalar_havuz_getir():array{
        self::autoInit();
        return $_SESSION['plan']['istisna']['havuz'];
    }

    public static function istisna_havuzda_olmayanlar():array{
        return array_diff_key(
            $_SESSION['plan']['istisna']['db'],
            $_SESSION['plan']['istisna']['havuz']
        );
    }

    /* ========== AŞAĞIDAKİLER CONTROLLER’DA KULLANILMIYOR ========== */

    //**********????*********///
    public static function istisna_user_al($tarih, $isim){
        self::autoInit();
        echo "<pre>";
        var_dump($_SESSION);
        echo "</pre>";
    }

    //**********????*********///
    public static function istisna_session_sil(string $tarih):void{
        unset( $_SESSION['plan']['istisna'][$tarih]);
    }

    public static function istisna_havuz_adet():int{
        return count( self::istisnalar_havuz_getir() );
    }
    //**********????*********///
    public static function istisna_post_kontrol():bool|string|null{
        self::autoInit();
        $kontrol=self::tarih_araligi_belirlenmis();
        if( $kontrol!==true ) return $kontrol;

        if(!isset($_POST["istisna_gun_ekle"]) || !isset($_POST["tarih"]) || !isset($_POST["isim"]) )  return null;

        $tarih=trim($_POST["tarih"]);
        $isim=trim($_POST["isim"]);

        if(strlen($isim)<3 || strlen($isim)>32 ) return "İsim en az 3 , en fazla 32 karakter olabilir!";
        if(!self::tarih_bicimi_dogru($tarih)) return "Tarih biçimi doğru değil!";

        $baslangic= $_SESSION["plan"]["tarih_baslangic"];
        $bitis= $_SESSION["plan"]["tarih_bitis"];
        if( $tarih<$baslangic ||  $tarih >$bitis ) return "İstisna tarihi plânlanan tarih aralığının dışında olamaz!";

        $durum = self::istisna_ekle_user($isim , $tarih);
        if($durum!==true ) return $durum;
        return true;
    }

    //**********????*********///
    public static function istisna_ekle_user(string $isim, string $tarih):bool|string{

        self::autoInit();
        if(!self::tarih_bicimi_dogru($tarih)) return "Tarih biçimi doğru değil!";
        $isim=trim($isim);
        if( $isim==='' ) return "İsim boş bırakılamaz";

        if( !isset($_SESSION["plan"]["istisna"]["user"])  || !is_array($_SESSION["plan"]["istisna"]["user"]) ){
            $_SESSION["plan"]["istisna"]["user"]=[];
        }

        if( isset($_SESSION["plan"]["istisna"]["user"][$tarih]) ) return "Bu tarih zaten var";

        $_SESSION["plan"]["istisna"]["user"][$tarih]=$isim ;
        return true;
    }

    //**********????*********///
    public static function isitisnalar_user(){
        self::autoInit();
    }
}
