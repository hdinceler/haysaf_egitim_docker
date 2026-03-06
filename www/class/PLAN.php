<?php 
// PLAN.php : ana class
declare(strict_types=1);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . "/traits/plan/tarih.php";
require_once __DIR__ . "/traits/plan/istisna.php";
require_once __DIR__ . "/traits/plan/ders.php";
require_once __DIR__ . "/traits/plan/rapor.php";

final class PLAN{
    use tarih,istisna,ders,rapor;

    private function __construct() {}
    public function __clone() {}
    public function __wakeup() {}

    private static bool $initialized=false;

    private const SESSION_SCHEMA = [
        'tarih_baslangic' => '',
        'tarih_bitis'     => '',
        'gun_sayisi_istisna'      => '',
        'gun_sayisi_net'      => '',
        'dersler'         => '',
        'uniteler'        => '',
        'rapor'           => '',
        'hata'            => [],
        'istisna'         => [
            'db'     => [],
            'havuz'=>[],
            'net'   => [],
        ],
    ];

    private const PLAN_MESSAGE_SCHEMA=[
        'tarih_baslangic' => 'Başlangıç Tarihi',
        'tarih_bitis'     => 'Bitiş Tarihi',
        'gun_sayisi'      => 'Gün Sayısı',
        'istisna_gunler'  => 'İstisnâ Günler',
        'dersler'         => 'Dersler',
        'uniteler'        => 'Üniteler',
        'rapor'           => 'Raporlama',
        ];
    
    public static function mesajlar_default():array{
        $buff= [];
        foreach(self::PLAN_MESSAGE_SCHEMA as $key=>$title  ){
            $buff[$key]=[
                'title'=>$title,
                'type'=>'error',
                'text'=>'...',
            ];
        }
        return $buff;
    }

    public static function mesaj(string $key , string $text, string $type):array{
        return [
            'title'=>self::PLAN_MESSAGE_SCHEMA[$key] ?? $key,
            'text'=>$text,
            'type'=>$type,
        ]; 
    }
    
    private static function autoInit():void{
        if(!self::$initialized) self::init();
    }

    public static function init():void{
       if(self::$initialized) return;
       $_SESSION['plan']=array_replace_recursive(
            self::SESSION_SCHEMA,
            $_SESSION['plan']??[]
       );
       self::$initialized=true;
    }

    public static function sifirla():void{
        $_SESSION['plan'] =  self::SESSION_SCHEMA;
        self::$initialized=true;
    }
    
    public static function post_edildi(string $key): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST'
            && array_key_exists($key, $_POST);
    }

    public static function ico(?string $type):string{
        if( $type==='success'){
            return  '<span class="text-green">✔</span>';
        } if( $type==='error'){
            return  '<span class="text-red">❌</span>';
        }
        return'';
    }

}