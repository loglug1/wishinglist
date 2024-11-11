<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Update Item';

if (!isAuthenticated()) {
    redirectTo('/login');
}

if (!isAdmin()) {
    $message = urlencode('You do not have permission to access this page!');
    redirectTo("/?w={$message}");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemId = $_POST['itemId'];
    if (isset($_POST['delete'])) {
        deleteItem($itemId);
        $message = urlencode("Successfully deleted item.");
        redirectTo("/adminItems/?m={$message}");
    }
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $link = trim($_POST['link']);

    updateItem($itemId, $title, $description, $link);
    $message = urlencode('Successfully updated item!');
    redirectTo("/adminItems/?m={$message}");
}

$itemId = (isset($_GET['i'])) ? $_GET['i'] : NULL;
if  ($itemId == NULL) {
    $message = urlencode('An error ocurred.');
    redirectTo("/?w={$message}");
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
    <div class='w3-panel w3-gray w3-padding w3-round-large w3-cell-middle' style='width: 50%; margin-left: auto; margin-right: auto;'>
    <form method=POST action='/updateItem/'>
        <label for=title>Item Name: </label><br><input type=text id=title name=title {$titleValueTag} class='w3-input w3-border w3-round-large' maxlength=255 required><br>
        <label for=description>Item Description: </label><br><textarea id=description name=description class='w3-input w3-border w3-round-large'n maxlength=280>{$description}</textarea><br>
        <label for=link>Link To Item: </label><br><input type=text id=link name=link {$linkValueTag} class='w3-input w3-border w3-round-large' maxlength=255><br>
        <input type=hidden name=itemId value={$itemId}>
        <input type=submit id=submit name=submit value='Update Item' class='w3-button w3-round-large w3-dark-gray w3-hover-black'>
    </form>
    <form method=POST action='/updateItem/'>
        <input type=hidden name=delete value=1>
        <input type=hidden name=itemId value={$itemId}>
        <input type=submit name=submit value='Delete Item' class='w3-margin-top w3-button w3-round-large w3-red w3-hover-black'>
    </form>
    </div>";
}

include __DIR__ . '/../../include/page-template.php';
?>
