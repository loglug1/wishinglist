<?php
require_once __DIR__ . '/../include/user.php';
require_once __DIR__ . '/../include/pages.php';

// shows access denied if 
if ($profile['priviledge'] < $pages[$page]['priviledge']) {
    if (!isset($profile['id'])) {
        goToPage('login', '?message=noAuth');
    } else {
        goToPage('home', '?message=accessErr');
    }
}

//handles basically all actions by client
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['submission-action'] ?? 'logout';
    switch($action) {
        case 'login':
        case 'logout':
        case 'link':
    }
    if ($action == 'login') {
        //TODO: sanitize input
        auth($_POST['username'], $_POST['password']);
    }
    if ($action == 'logout') {
        session_destroy();
        unset($_POST);
        gotoPage('login');
    }
    if ($action == 'link') {
        $location = $_POST['location'];
        goToPage($location);
    }
}

?>