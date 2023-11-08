<?php
require_once __DIR__ . '/pages.php';
require_once __DIR__ . '/connect.php';

function auth($username, $password) {
    $account = getAccountByUsername($username);
    if (($account !== NULL) && password_verify($password, $account['password'])) {
        $_SESSION['accountId'] = $account['id'];
        $_SESSION['profile'] = getProfileByAccountId($account['id']);
        gotoPage('home');
    } else {
        gotoPage('login', '?message=incorrect');
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

?>