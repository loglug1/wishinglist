<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Manage Users';

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
    $allUsers = getAllUsers();
    $tableContents = '';
    foreach ($allUsers as $user) {
        $firstName = ($user['firstName'] != NULL) ? htmlspecialchars($user['firstName']) : '';
        $lastName = ($user['lastName'] != NULL) ? htmlspecialchars($user['lastName']) : '';
        $username = ($user['username'] != NULL) ? htmlspecialchars($user['username']) : '';
        $firstName = htmlspecialchars($firstName);
        $lastName = htmlspecialchars($lastName);
        $username = htmlspecialchars($username);
        $tableContents .= "<tr><td>{$firstName} {$lastName}<td>{$username}</td><td><a href='/updateUser?u={$user['id']}'>View</a></td></tr>";
    }
    return "
        <div class='w3-section'>
        <a  class='w3-margin-left w3-margin-bottom w3-button w3-gray w3-hover-black' href='/createUser'>Create New User</a>
        <table class='w3-table w3-striped w3-bordered w3-border'>
        <thead>
        <tr class='w3-gray'><th>Title</th><th>Username</th><th>Actions</th></tr>
        </thead>
        {$tableContents}
        </table>
        </div>";
}

include __DIR__ . '/../../include/page-template.php';
?>