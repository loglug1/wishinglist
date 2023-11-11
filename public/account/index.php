<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'My Account';

if (!isAuthenticated()) {
    header('Location: /login');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['accountId'];
    if (isset($_POST['delete'])) {
        if ($userId == -1) {
            $message = urlencode("You can't delete the super admin!");
            header("Location: /?w={$message}");
            exit();
        }
        destroyUserAuthTokens($userId);
        deleteProfile($userId);
        deleteAccount($userId);
        logout();
        $message = urlencode("Successfully deleted user.");
        header("Location: /login/?m={$message}");
    }
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $priviledge = getUserById($userId)['priviledge'];

    $diffPasswords = $password !== $password2;
    $usernameOwner = getAccountByUsername($username);
    $usernameTaken = $usernameOwner != NULL && $usernameOwner['id'] != $userId;
    if (!$usernameTaken && !$diffPasswords) {
        updateAccount($userId, $username, $password);
        updateProfile($userId, $firstName, $lastName, $priviledge);
        $message = urlencode('Successfully updated account!');
        header("Location: /?m={$message}");
        exit();
    } else {
        $firstNameEnc = urlencode($firstName);
        $lastNameEnc = urlencode($lastName);
        $usernameEn = urlencode($username);
        header("Location: /account/?diffPasswords={$diffPasswords}&usernameTaken={$usernameTaken}&firstName={$firstName}&lastName={$lastName}&username={$username}");
        exit();
    }
}

$user = getUserById($_SESSION['accountId']);


function Title() {
    global $pageName;
    return $pageName;
}

function PageHeader() {
    global $pageName, $user;
    return "<h1>{$pageName}</h1>";
}

function Main() {
    global $pageName, $user;
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
    $diffPasswordsSpan = '';
    if (isset($_GET['usernameTaken'])) {
        $usernameTakenSpan = ($_GET['usernameTaken']) ? "<span class=w3-text-red>Username taken!</span><br>" : '';
    }
    if (isset($_GET['diffPasswords'])) {
        $diffPasswordsSpan = ($_GET['diffPasswords']) ? "<span class=w3-text-red>Passwords don't match!</span><br>" : '';
    }

    return "
    <form method=POST action='/account/'>
        <label for=firstName>First Name: </label><input type=text id=firstName name=firstName {$firstNameValueTag} maxlength=255 required><br>
        <label for=lastName>Last Name: </label><input type=text id=lastName name=lastName {$lastNameValueTag} maxlength=255 required><br>
        {$usernameTakenSpan}
        <label for=username>Username: </label><input type=text id=username name=username {$usernameValueTag} maxlength=255 required><br>
        {$diffPasswordsSpan}
        <label for=password>Password: </label><input type=password id=password name=password minlength=5><br>
        <label for=password2>Re-enter Password: </label><input type=password id=password2 name=password2 minlength=5><br>
        <input type=submit id=submit name=submit value='Update User'>
    </form>
    <form method=POST action='/account/'>
        <input type=hidden name=delete value=1>
        <input type=submit name=submit value='Delete Account'>
    </form>
    ";
}

include __DIR__ . '/../../include/page-template.php';
?>