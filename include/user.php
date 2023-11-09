<?php
require_once __DIR__ . '/pages.php';
require_once __DIR__ . '/connect.php';

function auth($username, $password) {
    $account = getAccountByUsername($username);
    if (($account != NULL) && password_verify($password, $account['password'])) {
        $_SESSION['accountId'] = $account['id'];
        $_SESSION['profile'] = getProfileByAccountId($account['id']);
        gotoPage('home');
    } else {
        gotoPage('login', ['message' => "Incorrect username or password!"]);
    }
}

function getProfileByAccountId($accountId) {
    global $pdo;
    $statement = $pdo->prepare("SELECT * FROM tbl_profiles WHERE accountId=:accountId LIMIT 0,1");
    $statement->bindValue(":accountId", $accountId);
    return ($statement->execute()) ? $statement->fetch() : NULL;
}

function getAccountByUsername($username) {
    global $pdo;
    $statement = $pdo->prepare("SELECT * FROM tbl_accounts WHERE username=:username LIMIT 0,1");
    $statement->bindValue(":username", $username);
    return ($statement->execute()) ? $statement->fetch() : NULL;
}

function getAccountById($id) {
    global $pdo;
    $statement = $pdo->prepare("SELECT * FROM tbl_accounts WHERE id=:id LIMIT 0,1");
    $statement->bindValue(":id", $id);
    return ($statement->execute()) ? $statement->fetch() : NULL;
}

function createProfile($username, $firstName, $lastName, $priviledge) {
    global $pdo;
    $accountId = getAccountByUserName($username)['id'];
    $profile = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'priviledge' => $priviledge,
        'accountId' => $accountId
    ];
    $statement = $pdo->prepare("INSERT INTO tbl_profiles (firstName, lastName, priviledge, accountId) VALUES (:firstName, :lastName, :priviledge, :accountId);");
    if (!$statement->execute($profile)) {
        die("error");
    }
}
function createAccount($username, $password) {
    global $pdo;
    $account = [
        'username' => $username,
        'password' => password_hash($password, PASSWORD_BCRYPT)
    ];
    $statement = $pdo->prepare("INSERT IGNORE INTO tbl_accounts (username, password) VALUES (:username, :password);");
    if (!$statement->execute($account)) {
        die("error");
    }
}

?>