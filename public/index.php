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
    return "
        <p>Welcome Home!</p>";
}

include __DIR__ . '/../include/page-template.php';
?>