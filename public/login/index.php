<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Login';

if (isAuthenticated()) {
    header('Location: /');
    exit();
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
        <form method=POST action='/login/'>
            <label for=username>Username: </label><input type=text id=username name=username><br>
            <label for=password>Password: </label><input type=password id=password name=password><br>
            <input type=submit name=submit value=Login>
        </form>
        <h3>
            Don't have an account? 
            <a href='/register'>Create One</a>
        </h3>";
}

include __DIR__ . '/../../include/page-template.php';
?>