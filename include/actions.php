<?php
require_once __DIR__ . '/../include/user.php';
require_once __DIR__ . '/../include/pages.php';

// shows access denied if 
if ($profile['priviledge'] < $page['priviledge']) {
    if (!isset($profile['id'])) {
        goToPage('login', ['message' => "You need an account to visit this page!"]);
    } else {
        goToPage('home', ['message' => "Access Denied"]);
    }
}

//handles basically all actions by client
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['submission-action'] ?? 'logout';
    switch($action) {
        case 'login':
            //TODO: sanitize input
            auth($_POST['username'], $_POST['password']);
            break;
        case 'logout':
            session_destroy();
            unset($_POST);
            gotoPage('login');
            break;
        case 'link':
            $location = $_POST['location'];//TODO: create foreach that will convert post to params
            goToPage($location, $params);
            break;
        case 'register':
            $params = [
                'firstName' => trim($_POST['firstName']),
                'lastName' => trim($_POST['lastName']),
                'username' => trim($_POST['username'])
            ];
            $passwordsDiff = $_POST['password'] !== $_POST['password2'];
            $usernameExists = getAccountByUsername($params['username']) != NULL;
            if (!$usernameExists && !$passwordsDiff) {
                createAccount($params['username'], $_POST['password']);
                createProfile($params['username'], $params['firstName'], $params['lastName'], 1);
                auth($params['username'], $_POST['password']);
            } else {
                if($passwordsDiff) {
                    $params['diffPasswords'] = TRUE;
                }
                if($usernameExists) {
                    $params['usernameTaken'] = TRUE;
                }
                goToPage('register', $params);
            }
            break;
    }
}

?>