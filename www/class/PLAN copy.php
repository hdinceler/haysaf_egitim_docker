<?php 
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
final class PLAN{
 
    private function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    private static bool $initialized=false;

    private static function autoInit():void{
        if(!self::$initialized) self::init();
    }

    public static function init():void{
        if(self::$initialized) return;
        if(  !isset($_SESSION["plan"]) || !is_array($_SESSION["plan"]) ){ $_SESSION["plan"] =[]; } 
        if(!isset($_SESSION["plan"]["tarih_baslangic"]) || !self::tarih_bicimi_dogru($_SESSION["plan"]["tarih_baslangic"])  ){$_SESSION["plan"]["tarih_baslangic"]= ''; }
        if(!isset($_SESSION["plan"]["tarih_bitis"]) || !self::tarih_bicimi_dogru($_SESSION["plan"]["tarih_bitis"])  ){$_SESSION["plan"]["tarih_bitis"]='' ; }
        if(  !isset($_SESSION["plan"]["istisna"]) || !is_array($_SESSION["plan"]["istisna"]) ){ $_SESSION["plan"]["istisna"] =[]; } 
        if(  !isset($_SESSION["plan"]["istisna"]["db"]) || !is_array($_SESSION["plan"]["istisna"]["db"]) ){ $_SESSION["plan"]["istisna"]["db"] =[]; } 
        if(  !isset($_SESSION["plan"]["istisna"]["user"]) || !is_array($_SESSION["plan"]["istisna"]["user"]) ){ $_SESSION["plan"]["istisna"]["user"] =[]; } 
        if(  !isset($_SESSION["plan"]["istisna"]["toplam"]) || !is_array($_SESSION["plan"]["istisna"]["toplam"]) ){ $_SESSION["plan"]["istisna"]["toplam"] =[]; } 
        self::$initialized=true;
    }

    public static function sifirla(){
        $_SESSION["plan"]=[];
        $_SESSION["plan"]["hata"]=[];
        self::$initialized=false;
        self::init();
    }

    public static function tarih_araligi_gecerli(string $baslangic,string  $bitis):bool|string{
        if($baslangic==='' || $bitis==='') return " Her iki tarih aralığı seçilmeli";
        if(!self::tarih_bicimi_dogru($baslangic)) return "Başlangıç tarihi doğru biçimde değil";
        if(!self::tarih_bicimi_dogru($bitis)) return "Bitiş tarihi doğru biçimde değil";
        if(  strtotime($bitis) <= strtotime($baslangic) ) return "Bitiş tarihi , başlangıç tarihinden büyük olmalı";
        return true;    
    }

    public static function tarih_araligi_kontrol(?string $baslangic, ?string $bitis): bool|array{
        self::autoInit();

        $errors = [];

        $baslangic = trim((string)$baslangic);
        $bitis     = trim((string)$bitis);

        // boşluk kontrolleri
        if ($baslangic === '') {
            $errors[] = "Başlangıç tarihi seçilmemiş";
        }

        if ($bitis === '') {
            $errors[] = "Bitiş tarihi seçilmemiş";
        }

        // format kontrolleri
        if ($baslangic !== '' && !self::tarih_bicimi_dogru($baslangic)) {
            $errors[] = "Başlangıç tarihi biçimi geçersiz";
        }

        if ($bitis !== '' && !self::tarih_bicimi_dogru($bitis)) {
            $errors[] = "Bitiş tarihi biçimi geçersiz";
        }

        // mantıksal karşılaştırma
        if (
            $baslangic !== '' && $bitis !== '' &&
            self::tarih_bicimi_dogru($baslangic) &&
            self::tarih_bicimi_dogru($bitis)
        ) {
            if ($bitis <= $baslangic) {
                $errors[] = "Bitiş tarihi, başlangıç tarihinden büyük olmalıdır";
            }
        }

        return empty($errors) ? true : $errors;
    }

    public static function tarih_araligi_belirlenmis():bool|string{
        self::autoInit();
        if(empty($_SESSION["plan"]["tarih_baslangic"]) ) return "Başlangıç tarihi belirlenmemiş!";
        if(empty($_SESSION["plan"]["tarih_bitis"]) ) return "Bitiş tarihi belirlenmemiş!";
        $baslangic=$_SESSION["plan"]["tarih_baslangic"];
        $bitis=$_SESSION["plan"]["tarih_bitis"];
        return self::tarih_araligi_gecerli($baslangic,$bitis);
        // return true;
    }
            
 
    public static function tarih_ata(string $baslangic , string $bitis):void{
        self::autoInit();
        $_SESSION["plan"]["tarih_baslangic"]=$baslangic;
        $_SESSION["plan"]["tarih_bitis"]=$bitis;
        self::gun_sayisi_ata();
    }

    public static function tarih_araligi_post_kontrol():bool|array|null{
        self::autoInit();
        if(!isset($_POST["tarih_araligi_belirle"]))  return null;

        $baslangic=trim((string)$_POST["tarih_baslangic"]??'');
        $bitis=trim((string)$_POST["tarih_bitis"]??'');

        $durum= self::tarih_araligi_kontrol($baslangic,$bitis) ;
        if($durum !==true) return $durum;
        self::tarih_ata($baslangic,$bitis) ;

        return true;
    }

    public static function gun_sayisi_ata():bool{
        $kontrol=self::tarih_araligi_belirlenmis();
        if( $kontrol!==true) return false;
        $baslangic= $_SESSION["plan"]["tarih_baslangic"];
        $bitis=$_SESSION["plan"]["tarih_bitis"] ;
        $fark= ( strtotime($bitis) - strtotime($baslangic) ) / 86400  ; // aynı gün 0 olur hata üretir en az 1 gün olmalı
        $_SESSION["plan"]["gun_sayisi"]= max( 0, (int)$fark );
        return true;
    }

    public static function istisna_user_al($tarih, $isim){
        self::autoInit();
        echo "<pre>";
        var_dump($_SESSION);        
        echo "</pre>";
        // if(isset($_SESSION["plan"]["istisna"]))
    }

    private static function tarih_bicimi_dogru(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }


    public static function tarih_tr(string $date): string{
        // Beklenen format: YYYY-MM-DD
        if (strlen($date) !== 10 || $date[4] !== '-' || $date[7] !== '-') {
            return $date; // bozuksa olduğu gibi döndür
        }

        static $aylar = [
            1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
            5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
            9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
        ];

        $yil = substr($date, 0, 4);
        $ay  = (int) substr($date, 5, 2);
        $gun = (int) substr($date, 8, 2);

        return $gun . ' ' . ($aylar[$ay] ?? '') . ' ' . $yil;
    }

    public static function istisnalar_db_getir(string $tarih_baslangic,string  $tarih_bitis):array{
        self::autoInit();
        $rows=DB::readRaw(
        "SELECT tarih, isim from tatiller WHERE tarih BETWEEN :baslangic AND :bitis ORDER BY tarih ASC",
            ["baslangic"=>$tarih_baslangic,"bitis"=> $tarih_bitis]
        );
        $_SESSION["plan"]["istisna"]["db"]=is_array( $rows ) ? array_column( $rows,"isim","tarih"): [];
        return $_SESSION["plan"]["istisna"]["db"];
    }

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

    public static function isitisnalar_user(){
        self::autoInit();
        // if( isset($_SESSION["plan"]) && is_array() )
    }

}