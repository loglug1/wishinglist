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
    <form method=POST action='/createItem/'>
        <label for=title>Item Name: </label><input type=text id=title name=title maxlength=255 required><br>
        <label for=description>Item Description: </label><br><textarea id=description name=description maxlength=280></textarea><br>
        <label for=link>Link To Item: </label><input type=text id=link name=link maxlength=255><br>
        <input type=submit id=submit name=submit value='Create Item'>
    </form>";
}

include __DIR__ . '/../../include/page-template.php';
?>