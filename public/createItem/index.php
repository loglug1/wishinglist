<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Wish for an Item';

if (!isAuthenticated()) {
    header('Location: /login');
    exit();
}

if (!isAdmin()) {
    $message = urlencode('You do not have permission to access this page!');
    header("Location: /?w={$message}");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);

    createItem($title, $description, $link);
    $message = urlencode('Successfully created item!');
    header("Location: /adminItems/?m={$message}");
    exit();
}

function Title() {
    global $pageName;
    return $pageName;
}

function PageHeader() {
    global $pageName, $item;
    return "<h1>{$pageName}</h1>";
}

function Main() {
    global $pageName, $itemId, $item;

    return "
    <div class='w3-panel w3-gray w3-padding w3-round-large w3-cell-middle' style='width: 50%; margin-left: auto; margin-right: auto;'>
    <form method=POST action='/createItem/'>
        <label for=title>Item Name: </label><input type=text id=title name=title class='w3-input w3-border w3-round-large' maxlength=255 required><br>
        <label for=description>Item Description: </label><br><textarea id=description name=description class='w3-input w3-border w3-round-large' maxlength=280></textarea><br>
        <label for=link>Link To Item: </label><input type=text id=link name=link class='w3-input w3-border w3-round-large' maxlength=255><br>
        <input type=submit id=submit name=submit value='Create Item' class='w3-button w3-round-large w3-dark-gray w3-hover-black'>
    </form>
    </div>";
}

include __DIR__ . '/../../include/page-template.php';
?>