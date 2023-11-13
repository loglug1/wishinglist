<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Create User';

if (!isAuthenticated()) {
    header('Location: /login');
    exit();
}

if (!isAdmin()) {
    $message = urlencode('You do not have permission to access this page!');
    header("Location: /?w={$message}");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $priviledge = (isset($_POST['admin'])) ? 1 : 0;
    
    $usernameOwner = getAccountByUsername($username);
    $usernameTaken = $usernameOwner != NULL;
    if (!$usernameTaken) {
        $userId = createAccount($username, $password);
        createProfile($userId, $firstName, $lastName, $priviledge);
        $message = urlencode('Successfully created user!');
        header("Location: /admin/?m={$message}");
        exit();
    } else {
        header("Location: /updateUser/?u={$userId}&usernameTaken={$usernameTaken}&firstName={$firstName}&lastName={$lastName}&username={$username}&priviledge={$priviledge}");
        exit();
    }
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
    $firstName = (isset($_GET['firstName'])) ? $_GET['firstName'] : NULL;
    $lastName = (isset($_GET['lastName'])) ? $_GET['lastName'] : NULL;
    $username = (isset($_GET['username'])) ? $_GET['username'] : NULL;
    $priviledge = (isset($_GET['priviledge'])) ? $_GET['priviledge'] : 0;
    $firstName = ($firstName != NULL) ? htmlspecialchars($firstName) : NULL;
    $lastName = ($lastName != NULL) ? htmlspecialchars($lastName) : NULL;
    $username = ($username != NULL) ? htmlspecialchars($username) : '';
    $firstNameValueTag = ($firstName != NULL) ? " value='{$firstName}' " : '';
    $lastNameValueTag = ($lastName != NULL) ? " value='{$lastName}' " : '';
    $checked = ($priviledge > 0) ? ' checked' : '';
    $usernameValueTag = " value='{$username}' ";
    $usernameTakenSpan = '';
    if (isset($_GET['usernameTaken'])) { //have to check if it is set and true because it is always set whether true or not
        $usernameTakenSpan = ($_GET['usernameTaken']) ? "<span class=w3-text-red>Username already exists!</span><br>" : '';
    }

    return "
    <div class='w3-panel w3-gray w3-padding w3-round-large w3-cell-middle' style='width: 50%; margin-left: auto; margin-right: auto;'>
    <form method=POST action='/createUser/'>
        <label for=firstName>First Name: </label><input type=text id=firstName name=firstName {$firstNameValueTag} class='w3-input w3-border w3-round-large'><br>
        <label for=lastName>Last Name: </label><input type=text id=lastName name=lastName {$lastNameValueTag} class='w3-input w3-border w3-round-large'><br>
        {$usernameTakenSpan}
        <label for=username>Username: </label><input type=text id=username name=username {$usernameValueTag} class='w3-input w3-border w3-round-large' required><br>
        <label for=password>Password: </label><input type=password id=password name=password class='w3-input w3-border w3-round-large'><br>
        <label for=admin>Is admin? </label><input type=checkbox id=admin name=admin class='w3-check' {$checked}><br>
        <input type=submit id=submit name=submit value='Create User' class='w3-margin-top w3-button w3-round-large w3-dark-gray w3-hover-black'>
    </form>
    </div>";
}

include __DIR__ . '/../../include/page-template.php';
?>