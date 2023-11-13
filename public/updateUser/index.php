<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Update User';

if (!isAuthenticated()) {
    redirectTo('/login');
}

if (!isAdmin()) {
    $message = urlencode('You do not have permission to access this page!');
    redirectTo("/?w={$message}");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    if (isset($_POST['delete'])) {
        if ($userId == -1) {
            $message = urlencode("You can't delete the super admin!");
            redirectTo("/admin/?w={$message}");
        }
        deleteUser($userId);
        $message = urlencode("Successfully deleted user.");
        redirectTo("/admin/?m={$message}");
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
        redirectTo("/admin/?m={$message}");
    } else {
        redirectTo("/updateUser/?u={$userId}&usernameTaken={$usernameTaken}&firstName={$firstName}&lastName={$lastName}&username={$username}");
    }
}

$editUserId = (isset($_GET['u'])) ? $_GET['u'] : NULL;
if  ($editUserId == NULL) {
    $message = urlencode('An error ocurred.');
    redirectTo("/?w={$message}");
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
    $priviledge = (isset($_GET['priviledge'])) ? $_GET['priviledge'] : $user['priviledge'];
    $firstName = ($firstName != NULL) ? htmlspecialchars($firstName) : NULL;
    $lastName = ($lastName != NULL) ? htmlspecialchars($lastName) : NULL;
    $username = ($username != NULL) ? htmlspecialchars($username) : '';
    $firstNameValueTag = ($firstName != NULL) ? " value='{$firstName}' " : '';
    $lastNameValueTag = ($lastName != NULL) ? " value='{$lastName}' " : '';
    $usernameValueTag = " value='{$username}' ";
    $checked = ($priviledge > 0) ? ' checked' : '';
    $usernameTakenSpan = '';
    if (isset($_GET['usernameTaken'])) {
        $usernameTakenSpan = ($_GET['usernameTaken']) ? "<span class=w3-text-red>Username taken!</span><br>" : '';
    }

    return "
    <div class='w3-panel w3-gray w3-padding w3-round-large w3-cell-middle' style='width: 50%; margin-left: auto; margin-right: auto;'>
    <form method=POST action='/updateUser/'>
        <label for=firstName>First Name: </label><input type=text id=firstName name=firstName {$firstNameValueTag} class='w3-input w3-border w3-round-large'><br>
        <label for=lastName>Last Name: </label><input type=text id=lastName name=lastName {$lastNameValueTag} class='w3-input w3-border w3-round-large'><br>
        {$usernameTakenSpan}
        <label for=username>Username: </label><input type=text id=username name=username {$usernameValueTag} class='w3-input w3-border w3-round-large' required><br>
        <label for=password>Password: </label><input type=password id=password name=password class='w3-input w3-border w3-round-large'><br>
        <label for=admin>Is admin? </label><input type=checkbox id=admin name=admin class='w3-check' {$checked}><br>
        <input type=hidden name=userId value={$editUserId}>
        <input type=submit id=submit name=submit value='Update User' class='w3-margin-top w3-button w3-round-large w3-dark-gray w3-hover-black'>
    </form>
    <form method=POST action='/updateUser/'>
        <input type=hidden name=delete value=1>
        <input type=hidden name=userId value={$editUserId}>
        <input type=submit name=submit value='Delete Account' class='w3-margin-top w3-button w3-round-large w3-red w3-hover-black'>
    </form>
    </div>";
}

include __DIR__ . '/../../include/page-template.php';
?>