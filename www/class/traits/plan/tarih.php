<?php 
//  __DIR__ . "/traits/plan/tarih.php"  : tarih tariti 
trait tarih{
    public static function tarih_gecerli_girilmis(string $anahtar): bool
    {
        self::autoInit();
        return isset($_SESSION['plan'][$anahtar])
            && $_SESSION['plan'][$anahtar] !== ''
            && self::tarih_bicimi_dogru($_SESSION['plan'][$anahtar]);
    }

    public static function tarih_alani_isle(
        string $key,
        string $label,
        string $value,
        array &$mesajlar
    ): bool {
        $durum = self::tarih_tek_kontrol($label, $value);

        if ($durum === true) {
            self::tarih_ata($key, $value);
            $mesajlar['plan'][$key] = self::mesaj( $key, self::tarih_tr_session($key),  'success' );
            return true;
        }

        self::tarih_temizle($key);
        $mesajlar['plan'][$key] = self::mesaj($key, $durum, 'error');
        return false;
    }
    
public static function tarih_araligi_getir(): array {
    self::autoInit();

    $kontrol = self::tarih_araligi_kontrol(
        $_SESSION['plan']['tarih_baslangic'],
        $_SESSION['plan']['tarih_bitis']
    );

    if ($kontrol !== true) {
        return [
            'result' => 0,
            'type'   => 'error',
            'error'  => $kontrol
        ];
    }

    $gun = (
        strtotime($_SESSION['plan']['tarih_bitis']) -
        strtotime($_SESSION['plan']['tarih_baslangic'])
    ) / 86400;

    return [
        'result' => (int)$gun,
        'type'   => 'success'
    ];
}


    public static function tarih_araligi_kontrol(string $baslangic,string  $bitis):bool|string{
        if($baslangic==='' || $bitis==='') return " Her iki tarih aralığı doğru seçilmeli";
        if(!self::tarih_bicimi_dogru($baslangic)) return "Başlangıç tarihi doğru biçimde değil";
        if(!self::tarih_bicimi_dogru($bitis)) return "Bitiş tarihi doğru biçimde değil";
        if(  strtotime($bitis) <= strtotime($baslangic) ) return "Bitiş tarihi , başlangıç tarihinden büyük olmalı";
        return true;    
    }
 
    public static function tarih_araligi_dogru_girilmis(): bool {
        $keys = ['tarih_baslangic', 'tarih_bitis'];
        // Tüm keyler dolu mu
        foreach ($keys as $key) {
            if (empty($_SESSION['plan'][$key])) return false;
        }

        // Aralık doğru mu
        return self::tarih_araligi_kontrol(
            $_SESSION['plan']['tarih_baslangic'],
            $_SESSION['plan']['tarih_bitis']
        ) === true;
    }

 
    public static function tarih_tek_kontrol(string $tarih_adi,string $tarih):bool|string{
        $tarih= trim( (string)$tarih );
        if($tarih==='') return "Boş bırakılamaz!";
        if( !self::tarih_bicimi_dogru($tarih) ) return  $tarih_adi . ": Biçim doğru değil";
        return true;
    }
    public static function tarih_baslangic():string{
        self::autoInit();
        return $_SESSION['plan']['tarih_baslangic'];
    }
    public static function tarih_bitis():string{
        self::autoInit();
        return $_SESSION['plan']['tarih_bitis'];
    }
    
    public static function tarih_araligi_belirlenmis():bool{
        $baslangic= $_SESSION["plan"]["tarih_baslangic"]??'';
        $bitis=$_SESSION["plan"]["tarih_bitis"]??'';
        return($baslangic!=='' &&  $bitis!=='');
    }
            
 
    public static function tarih_ata(string $tarih_adi , string $tarih):void{
        self::autoInit();
        $_SESSION["plan"][$tarih_adi]=$tarih;
    }
    public static function tarih_temizle(string $tarih_adi ):void{
        self::autoInit();
       $_SESSION["plan"][$tarih_adi]='';
    }
 
    public static function gun_sayisi_ata():bool|string{
        $kontrol=self::tarih_araligi_belirlenmis();
        if( $kontrol!==true) return false;
        $baslangic= $_SESSION["plan"]["tarih_baslangic"];
        $bitis=$_SESSION["plan"]["tarih_bitis"] ;
        $fark= ( strtotime($bitis) - strtotime($baslangic) ) / 86400  ; // aynı gün 0 olur hata üretir en az 1 gün olmalı
        $_SESSION["plan"]["gun_sayisi"]= max( 1, (int)$fark );
        return true;
    }

    public static function tarih_bicimi_dogru(string $date): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    
    public static function tarih_tr_session(string $key):string{
        return self::tarih_tr($_SESSION["plan"][$key]);
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



}