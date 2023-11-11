<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Update User';

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
    $userId = $_POST['userId'];
    if (isset($_POST['delete'])) {
        if ($userId == -1) {
            $message = urlencode("You can't delete the super admin!");
            header("Location: /admin/?w={$message}");
            exit();
        }
        destroyUserAuthTokens($userId);
        deleteProfile($userId);
        deleteAccount($userId);
        $message = urlencode("Successfully deleted user.");
        header("Location: /admin/?m={$message}");
    }
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $priviledge = (isset($_POST['admin'])) ? 1 : 0;
    if ($userId == -1) {
        $priviledge = 2;
    }
    
    $usernameOwner = getAccountByUsername($username);
    $usernameTaken = $usernameOwner != NULL && $usernameOwner['id'] != $userId;
    if (!$usernameTaken) {
        updateAccount($userId, $username, $password);
        updateProfile($userId, $firstName, $lastName, $priviledge);
        $message = urlencode('Successfully updated user!');
        header("Location: /admin/?m={$message}");
        exit();
    } else {
        header("Location: /updateUser/?u={$userId}&usernameTaken={$usernameTaken}&firstName={$firstName}&lastName={$lastName}&username={$username}");
        exit();
    }
}

$editUserId = (isset($_GET['u'])) ? $_GET['u'] : NULL;
if  ($editUserId == NULL) {
    $message = urlencode('An error ocurred.');
    header("Location: /?w={$message}");
}
$user = getUserById($editUserId);


function Title() {
    global $pageName;
    return $pageName;
}

function PageHeader() {
    global $pageName, $user;
    $username = htmlspecialchars($user['username']);
    return "<h1>Update User: {$username}</h1>";
}

function Main() {
    global $pageName, $editUserId, $user;
    $firstName = (isset($_GET['firstName'])) ? $_GET['firstName'] : $user['firstName'];
    $lastName = (isset($_GET['lastName'])) ? $_GET['lastName'] : $user['lastName'];
    $username = (isset($_GET['username'])) ? $_GET['username'] : $user['username'];
    $firstName = ($user['firstName'] != NULL) ? htmlspecialchars($user['firstName']) : NULL;
    $lastName = ($user['lastName'] != NULL) ? htmlspecialchars($user['lastName']) : NULL;
    $username = ($user['username'] != NULL) ? htmlspecialchars($user['username']) : '';
    $firstNameValueTag = ($firstName != NULL) ? " value='{$firstName}' " : '';
    $lastNameValueTag = ($lastName != NULL) ? " value='{$lastName}' " : '';
    $usernameValueTag = " value='{$username}' ";
    $usernameTakenSpan = '';
    if (isset($_GET['usernameTaken'])) {
        $usernameTakenSpan = ($_GET['usernameTaken']) ? "<span class=w3-text-red>Username taken!</span><br>" : '';
    }
    $checked = ($user['priviledge'] == 2) ? ' checked' : '';

    return "
    <form method=POST action='/updateUser/'>
        <label for=firstName>First Name: </label><input type=text id=firstName name=firstName {$firstNameValueTag}><br>
        <label for=lastName>Last Name: </label><input type=text id=lastName name=lastName {$lastNameValueTag}><br>
        {$usernameTakenSpan}
        <label for=username>Username: </label><input type=text id=username name=username {$usernameValueTag} required><br>
        <label for=password>Password: </label><input type=password id=password name=password><br>
        <label for=admin>Is admin? </label><input type=checkbox id=admin name=admin {$checked}><br>
        <input type=hidden name=userId value={$editUserId}>
        <input type=submit id=submit name=submit value='Update User'>
    </form>
    <form method=POST action='/updateUser/'>
        <input type=hidden name=delete value=1>
        <input type=hidden name=userId value={$editUserId}>
        <input type=submit name=submit value='Delete Account'>
    </form>";
}

include __DIR__ . '/../../include/page-template.php';
?>