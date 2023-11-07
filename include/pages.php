<?php

$jsonFileContents = file_get_contents(__DIR__ . '/../pages/index.json');
$pages = json_decode($jsonFileContents, true);

function gotoPage($page, $queryString = "") {
    $_SESSION['page'] = $page;
    //reload page and maintain GET variables
    header("Location: /" . $queryString);
    exit;
}

function getQueryString() {
    return ($_GET ? "?" . $_SERVER['QUERY_STRING'] : "");
}

function getTitle() {
    global $page, $pages;
    echo "WishingList" . (" - " . $pages[$page]['title'] ?: "");
}

function getHeader() {
    echo "<h1> hello world </h1>";
}

function getContent() {
    global $page, $pages;
    require __DIR__ . "/../pages/" . $pages[$page]['location'];
}

function getFooter() {
    echo "currently on ". $_SESSION['page'] ?: "foot";
}