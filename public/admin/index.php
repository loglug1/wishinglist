<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Admin Page';

if (!isAuthenticated()) {
    header('Location: /login');
    exit();
}

if (!isAdmin()) {
    $message = urlencode('You do not have permission to view this page!');
    header("Location: /?w={$message}");
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
    $allUsers = getAllUsers();
    $tableContents = '';
    foreach ($allUsers as $user) {
        $tableContents .= "<tr><td>{$user['firstName']} {$user['lastName']}<td>{$user['username']}</td><td><a href='/updateUser?u={$user['id']}'>View</a></td></tr>";
    }
    return "
        <table class='w3-table w3-striped w3-bordered w3-border'>
        <thead>
        <tr class='w3-gray'><th>Name</th><th>Username</th><th>Actions</th></tr>
        </thead>
        {$tableContents}
        </table>";
}

include __DIR__ . '/../../include/page-template.php';
?>