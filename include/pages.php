<?php

function gotoPage($pageId, $params = NULL) {
    unset($_POST);
    $_SESSION['params'] = $params;
    $_SESSION['pageId'] = $pageId;
    header("Location: /");
    exit;
}

function linkTo($pageId, $text) {
    echo // TODO: add foreach to encode params as hidden inputs
    "<form method=POST action='/'>
        <input type=hidden name=submission-action value=link>
        <input type=hidden name=location value='{$pageId}'>
        <input class=pageButton type=submit name=submit value='{$text}'>
    </form>";
}

function getQueryString() {
    return ($_GET ? "?" . $_SERVER['QUERY_STRING'] : "");
}

function getTitle() {
    global $page, $pages;
    echo "WishingList" . (" - " . $page['title'] ?: "");
}

function getHeader() {
    global $page, $params;
    echo "<h1>" . ($page['title'] ?: "Hello World!") . (isset($params['message']) ? " - " . $params['message'] : "") .  "</h1>";
}

function getContent() {
    global $page, $pages;
    require __DIR__ . "/../pages/" . $page['location'];
}

function getFooter() {
    echo var_dump($_SESSION);
}