<?
    $name = 'teste/imagem/oi/Tèrŕãfffśéẃqd.jpeg';
    echo preg_replace('/[`^~\'"]/', null, iconv( 'UTF-8', 'ASCII//IGNORE', $name));
?>