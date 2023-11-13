<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Wishinglist';

if (isAuthenticated()) {
    redirectTo('/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    authenticate($username, $password);
}

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
        <div class='w3-panel w3-gray w3-padding w3-round-large w3-cell-middle' style='width: 50%; margin-left: auto; margin-right: auto;'>
        <form method=POST action='/login/'>
            <label for=username>Username: </label><input type=text id=username name=username class='w3-input w3-border w3-round-large'><br>
            <label for=password>Password: </label><input type=password id=password name=password class='w3-input w3-border w3-round-large'><br>
            <input type=submit name=submit value=Login class='w3-button w3-round-large w3-dark-gray w3-hover-black'>
        </form>
        <p>Don't have an account? <a href='/register'>Create One</a></p>
        </div>";
}

include __DIR__ . '/../../include/page-template.php';
?>