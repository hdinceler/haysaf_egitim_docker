<?php 

function minify_css($css) {
    // Yorumları sil
    $css = preg_replace('!/\*.*?\*/!s', '', $css);
    // Boşlukları temizle
    $css = preg_replace('/\s+/', ' ', $css);
    $css = str_replace(
        [' {','{ ',' ;','; ',' :',': ', ', '],
        ['{','{',';',';',':',':',','],
        $css
    );
    return trim($css);
}
