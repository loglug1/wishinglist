<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Manage Items';

if (!isAuthenticated()) {
    redirectTo('/login');
}

if (!isAdmin()) {
    $message = urlencode('You do not have permission to view this page!');
    redirectTo("/?w={$message}");
}

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
    $allItems = getAllItems();
    $tableContents = '';
    foreach ($allItems as $item) {
        $title = ($item['title'] != NULL) ? htmlspecialchars($item['title']) : '';
        $link = ($item['link'] != NULL) ? htmlspecialchars($item['link']) : '';
        $statusText = ($item['claimedBy']) ? 'Claimed' : 'Unclaimed';
        $tableContents .= "<tr><td>{$title}<td><a href='{$link}'>{$link}</a></td><td>{$statusText}</td><td><a href='/updateItem?i={$item['id']}'>View</a></td></tr>";
    }
    return "
        <div class='w3-section'>
        <a class='w3-margin-left w3-margin-bottom w3-button w3-gray w3-hover-black' href='/createItem'>Add an Item</a>
        <table class='w3-table w3-striped w3-bordered w3-border'>
        <thead>
        <tr class='w3-gray'><th>Title</th><th>Link</th><th>Status</th><th>Actions</th></tr>
        </thead>
        {$tableContents}
        </table>
        </div>";
}

include __DIR__ . '/../../include/page-template.php';
?>