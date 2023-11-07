<?php
require_once __DIR__ . '/pages.php';

//handles submissions from pages
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['submit'] == 'Login') { // replace with hidden form elem
        //handle authentication
        unset($_POST);
        gotoPage('home', "?specialQuery=true");
    }
    if ($_POST['submit'] == 'Logout') { // replace with hidden form elem
        //clear session
        unset($_POST);
        gotoPage('login');
    }
}

function getUserData($userId) {
    require_once __DIR__ . "/connect.php";
    
}

$page = $_SESSION['page'] ?? 'login';
$userId = $_SESSION['userId'] ?? NULL;

?>