<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Create an Account';

if (isAuthenticated()) {
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    

    $diffPasswords = $password !== $password2;
    $usernameTaken = getAccountByUsername($username) != NULL;
    if (!$usernameTaken && !$diffPasswords) {
        $accountId = createAccount($username, $password);
        createProfile($accountId, $firstName, $lastName, 0);
        authenticate($username, $password);
    } else {
        header("Location: /register/?diffPasswords={$diffPasswords}&usernameTaken={$usernameTaken}&firstName={$firstName}&lastName={$lastName}&username={$username}");
        exit();
    }
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
    $firstNameValueTag = (isset($_GET['firstName'])) ? " value='{$_GET['firstName']}' " : '';
    $lastNameValueTag = (isset($_GET['lastName'])) ? " value='{$_GET['lastName']}' " : '';
    $usernameValueTag = (isset($_GET['username'])) ? " value='{$_GET['username']}' " : '';
    $usernameTakenSpan = '';
    $diffPasswordsSpan = '';
    if (isset($_GET['usernameTaken'])) {
        $usernameTakenSpan = ($_GET['usernameTaken']) ? "<span class=w3-text-red>Username taken!</span><br>" : '';
    }
    if (isset($_GET['diffPasswords'])) {
        $diffPasswordsSpan = ($_GET['diffPasswords']) ? "<span class=w3-text-red>Passwords don't match!</span><br>" : '';
    }

    return "
    <form method=POST action='/register/'>
        <label for=firstName>First Name: </label><input type=text id=firstName name=firstName {$firstNameValueTag} maxlength=255 required><br>
        <label for=lastName>Last Name: </label><input type=text id=lastName name=lastName {$lastNameValueTag} maxlength=255 required><br>
        {$usernameTakenSpan}
        <label for=username>Username: </label><input type=text id=username name=username {$usernameValueTag} maxlength=255 required><br>
        {$diffPasswordsSpan}
        <label for=password>Password: </label><input type=password id=password name=password minlength=5 required><br>
        <label for=password2>Re-enter Password: </label><input type=password id=password2 name=password2 minlength=5 required><br>
        <input type=submit id=submit name=submit value=Register>
    </form>
    <p>Already have an account? <a href=/login>Login</a>";
}

include __DIR__ . '/../../include/page-template.php';
?>