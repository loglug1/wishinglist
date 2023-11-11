<?php
require_once __DIR__ . '/../include/functions.php';

$pageName = 'Home';

if (!isAuthenticated()) {
    header('Location: /login');
    exit();
}

$user = getUserById($_SESSION['accountId']);

function Title() {
    global $pageName;
    return $pageName;
}

function PageHeader() {
    global $pageName;
    return "<h1>{$pageName}</h1>";
}

function Main() {
    global $pageName;
    $items = getUnclaimedItems();
    $itemsDiv = "<div class=''>";
    foreach ($items as $item) {
        $title = ($item['title'] != NULL) ? htmlspecialchars($item['title']) : '';
        $description = ($item['description'] != NULL) ? htmlspecialchars($item['description']) : '';
        $link = ($item['link'] != NULL) ? htmlspecialchars($item['link']) : '';
        $itemsDiv .= "<div class='w3-gray w3-panel w3-border'><h2>{$title}</h2><p>{$description}{$link}</p></div>";
    }
    $itemsDiv .= '</div>';
    return "<p>I want these things!</p> {$itemsDiv}";
}

include __DIR__ . '/../include/page-template.php';
?>