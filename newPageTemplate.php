<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Page';

if (!isAuthenticated()) {
    header('Location: /login');
    exit();
}

function Title() {
    global $pageName;
    return $pageName;
}

function Body() {
    global $pageName;
    return "<h1>{$pageName}</h1>";
}

include __DIR__ . '/../../include/page-template.php';
?>