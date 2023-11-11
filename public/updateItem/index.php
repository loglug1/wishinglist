<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Update User';

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
    $itemId = $_POST['itemId'];
    if (isset($_POST['delete'])) {
        deleteItem($itemId);
        $message = urlencode("Successfully deleted item.");
        header("Location: /adminItems/?m={$message}");
    }
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);

    updateItem($itemId, $title, $description, $link);
    $message = urlencode('Successfully updated item!');
    header("Location: /adminItems/?m={$message}");
    exit();
}

$itemId = (isset($_GET['i'])) ? $_GET['i'] : NULL;
if  ($itemId == NULL) {
    $message = urlencode('An error ocurred.');
    header("Location: /?w={$message}");
}
$item = getItemById($itemId);


function Title() {
    global $pageName;
    return $pageName;
}

function PageHeader() {
    global $pageName, $item;
    $itemName = htmlspecialchars($item['title']);
    return "<h1>Update Item: {$itemName}</h1>";
}

function Main() {
    global $pageName, $itemId, $item;
    $title = $item['title'];
    $description = $item['description'];
    $link = $item['link'];
    $title = ($title != NULL) ? htmlspecialchars($title) : NULL;
    $description = ($description != NULL) ? htmlspecialchars($description): '';
    $link = ($link != NULL) ? htmlspecialchars($link): NULL;
    $titleValueTag = ($title != NULL) ? " value='{$title}' " : '';
    $linkValueTag = ($link != NULL) ? " value='{$link}' " : '';

    return "
    <form method=POST action='/updateItem/'>
        <label for=title>Item Name: </label><input type=text id=title name=title {$titleValueTag} maxlength=255 required><br>
        <label for=description>Item Description: </label><br><textarea id=description name=description maxlength=280>{$description}</textarea><br>
        <label for=link>Link To Item: </label><input type=text id=link name=link {$linkValueTag} maxlength=255><br>
        <input type=hidden name=itemId value={$itemId}>
        <input type=submit id=submit name=submit value='Update Item'>
    </form>
    <form method=POST action='/updateItem/'>
        <input type=hidden name=delete value=1>
        <input type=hidden name=itemId value={$itemId}>
        <input type=submit name=submit value='Delete Item'>
    </form>";
}

include __DIR__ . '/../../include/page-template.php';
?>