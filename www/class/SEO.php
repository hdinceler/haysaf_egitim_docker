<?php
//"./class/SEO.php" class
require_once "config.php";
final class SEO {
    public static array $defaultData = [
        'title'       =>SITE_NAME,
        'description' =>DESCRIPTION,
        'keywords'    =>KEYWORDS,
        'canonical'   =>CANONICAL,
        'og_description'    =>OG_DESCRIPTION,
        'og_image'    =>OG_IMAGE,
    ];
 

    public static function update(array $newData): void {
        $safe = array_merge(self::$defaultData, $newData); // Eksik alanlar için varsayılanları koru
        echo "<title>" . htmlspecialchars((string) $safe['title'], ENT_QUOTES, 'UTF-8') . "</title>\n";
        echo "<meta name=\"description\" content=\"" . htmlspecialchars((string) $safe['description'], ENT_QUOTES, 'UTF-8') . "\">\n";
        echo "<meta name=\"keywords\" content=\"" . htmlspecialchars((string) $safe['keywords'], ENT_QUOTES, 'UTF-8') . "\">\n";
        echo "<link rel=\"canonical\" href=\"" . htmlspecialchars((string) $safe['canonical'], ENT_QUOTES, 'UTF-8') . "\">\n";
        echo "<meta property=\"og:title\" content=\"" . htmlspecialchars((string) $safe['keywords'], ENT_QUOTES, 'UTF-8') . "\">\n";
        echo "<meta property=\"og:image\" content=\"" . htmlspecialchars((string) $safe['og_image'], ENT_QUOTES, 'UTF-8') . "\">\n";
    }

 
    public static function sefLink(string $text): string {
        $text = strip_tags($text); // HTML etiketlerini temizle
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8'); // XSS'e karşı koruma

        $turkish = ['ş','Ş','ı','İ','ğ','Ğ','ü','Ü','ö','Ö','ç','Ç'];
        $english = ['s','s','i','i','g','g','u','u','o','o','c','c'];
        $text = str_replace($turkish, $english, $text);

        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);

        return trim($text, '-');
    }


}
