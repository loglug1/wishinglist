<?php

$jsonFileContents = file_get_contents(__DIR__ . '/../pages/index.json');
$pages = json_decode($jsonFileContents, true);

$jsonFileContents = file_get_contents(__DIR__ . '/../pages/messages.json');
$messages = json_decode($jsonFileContents, true);

function gotoPage($page, $queryString = "") {
    unset($_POST);
    $_SESSION['page'] = $page;
    //reload page and maintain GET variables
    header("Location: /" . $queryString);
    exit;
}

function linkTo($page, $text) {
    echo 
    "<form method=POST action='/'>
        <input type=hidden name=submission-action value=link>
        <input type=hidden name=location value='{$page}'>
        <input class=pageButton type=submit name=submit value='{$text}'>
    </form>";
}

function getQueryString() {
    return ($_GET ? "?" . $_SERVER['QUERY_STRING'] : "");
}

function getTitle() {
    global $page, $pages;
    echo "WishingList" . (" - " . $pages[$page]['title'] ?: "");
}

function getHeader() {
    global $messages, $pages, $page;
    echo "<h1>" . ($pages[$page]['title'] ?: "Hello World!") . (isset($_GET['message']) ? " - " . $messages[$_GET['message']] : "") .  "</h1>";
}

function getContent() {
    global $page, $pages;
    require __DIR__ . "/../pages/" . $pages[$page]['location'];
}

function getFooter() {
    echo var_dump($_SESSION);
}