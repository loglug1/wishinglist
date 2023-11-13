<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Wishinglist';

if (!isAuthenticated()) {
    header('Location: /login');
    exit();
}

logout();

function Title() {
    global $pageName;
    return $pageName;
}

function Nav() {
    return '';
}

function PageHeader() {
    global $pageName;
    return "<h1>{$pageName}</h1>";
}

function Main() {
    global $pageName;
    return "
        <p>You have successfully logged out. You may close the browser or <a href='/login'>go back to login.</a></p>";
}

include __DIR__ . '/../../include/page-template.php';
?>