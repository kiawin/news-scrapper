<?php

function extractTitle($site, $html) {

    $h = str_get_html($html);
    $returnValue = trim($h->find("title", 0)->plaintext);

    if ($site === "tmi") {

        $returnValue = explode("-", $returnValue);
        array_shift($returnValue);
        array_shift($returnValue);
        $returnValue = implode("", $returnValue);
        $returnValue = strstr($returnValue, "@", TRUE);

    } else if ($site === "freemalaysiakini") {

        $returnValue = strstr($returnValue, " | Free MalaysiaKini", TRUE);

    } else if ($site === "merdekareview-malay") {

        $returnValue = explode("-", $returnValue);
        array_shift($returnValue);
        $returnValue = implode("", $returnValue);

    } else if ($site === "mmail") {

        $returnValue = strstr($returnValue, " | The Malay Mail", TRUE);

    } else if ($site === "bernama") {

        $returnValue = explode("-", $returnValue);
        array_shift($returnValue);
        $returnValue = implode("", $returnValue);

    } else if ($site === "utusan") {

        $returnValue = $h->find("div.fullstory h3", 0)->plaintext;

    }

    return trim(html_entity_decode($returnValue, ENT_QUOTES, "UTF-8"));

}

function extractContent($site, $html) {

    $obj = NULL;
    $content = "";
    $h = str_get_html($html);

    if ($site === "thestar") $obj = $h->find("#story_content", 0);
    else if ($site === "tmi") $obj = $h->find("#article", 0);
    else if ($site === "freemalaysiakini") $obj = $h->find("#innerLeft .post", 0);
    else if ($site === "utusan") $obj = $h->find("div.fullstory", 0);
    else if ($site === "merdekareview-malay") $obj = $h->find("div.content", 0);
    else if ($site === "mmail") $obj = $h->find("#content-area", 0);
    else if ($site === "bernama") $obj = $h->find("p#news", 0);

    if ($obj === null) return FALSE;

    return trim($obj->plaintext);

}

function extractTags($html) {

    global $tags;

    $matchedTags = array();
    $html = strtolower($html);

    foreach($tags as $tag)
        if (preg_match("/[^a-zA-Z]${tag}[^a-zA-Z]/", " $html ") > 0) $matchedTags[] = $tag;

    return array_unique($matchedTags);

}

function bold($msg) {
    $msg=addDate($msg);
    echo "\033[1m$msg\033[0m\n";
}

function bullets($msg) {
    $msg=addDate(" * $msg");
    echo "$msg\n";
}

function newline() {
    echo "\n";
}

function red($msg) {
    $msg=addDate($msg);
    echo "\033[31m$msg\033[30m\n";
}

function errorlog($msg) {
    $msg=addDate($msg);
    @file_put_contents("error.log", "$msg\n", FILE_APPEND);
}

function addDate($msg) {
    return date("M y H:i:s:\t").$msg;
}

?>
