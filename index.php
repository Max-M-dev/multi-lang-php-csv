<?php

function getListLang($csvFile){
    $file_to_read = fopen($csvFile, 'r');
    $listLang = fgetcsv($file_to_read, 1000, ';');
    unset($listLang[0]);
    return array_values($listLang);
}
function getListText($csvFile,$lang){
//read the csv file into an array
    $file_to_read = fopen($csvFile, 'r');
    $listLang = fgetcsv($file_to_read, 1000, ';');
    $idLang = null;

    foreach ($listLang as $key => $value) {
        if($lang===$value){
            $idLang = $key;
        }
    }
    if(!$idLang){
        exit("There is no such language in the database");
    }
    while (!feof($file_to_read) ) {
        $temp = fgetcsv($file_to_read, 2000, ';');
        if(!empty($temp[0])){
            $lines[$temp[0]] = $temp[$idLang];
        }

    }
    fclose($file_to_read);

    return $lines;
}

/* ========= Cookie lang get/set ===========*/
$csvFile = 'lang.csv';
$defaultLang = 'uk';
$currentLang = null;
$listLang = getListLang($csvFile);


if(isset($_COOKIE['lang']) && in_array($_COOKIE['lang'],$listLang)) {
    $currentLang = $_COOKIE['lang'];
}else{
    $currentLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if(!in_array($currentLang,$listLang)){
        $currentLang = $defaultLang;
    }
    setcookie("lang", $currentLang, time() + 3600 * 24 * 30);
}

$l = getListText($csvFile,$currentLang);

?>

<!DOCTYPE html>
<html lang="<?= $currentLang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $l["doc__title"] ?></title>
</head>
<body>
    <?= $l["body__text"] ?>
    <ul>
       <?
       foreach ($listLang as $value) {
        echo '
        <li>
            <a href="javascript:void(0);" onclick="changeLang(\''.$value.'\')">'.$value.'</a>
        </li>';
    }
    ?>
</ul>
<script>
    function changeLang(lang){
        document.cookie = `lang=${lang}`;
        document.location.reload();
    }
</script>
</body>
</html>