<?php
    require_once __DIR__ . '/connect.php';
    session_start();

    $sessionCookieName = getenv('COOKIE_NAME') ?: 'wishinglistSession';

    function isAuthenticated() {
        global $sessionCookieName;
        if (isset($_SESSION['accountId'])) {
            return true;
        }
        if (isset($_COOKIE[$sessionCookieName])) {
            $token = $_COOKIE[$sessionCookieName];
            $authToken = getAuthToken($token);
            if ($authToken != NULL) {
                $_SESSION['accountId'] = $authToken['accountId'];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function authenticate($username, $password, $destination = '') {
        $account = getAccountByUsername($username);
        if (($account != NULL) && password_verify($password, $account['password'])) {
            $token = createAuthToken($account['id']);
            setTokenCookie($token);
            $_SESSION['accoutnId'] = $account['id'];
            header("Location: /{$destination}");
            exit();
        } else {
            $message = "Incorrect username or password";
            $encodedMessage = urlencode($message);
            header("Location: /login/?m={$encodedMessage}");
            exit();
        }
    }

    function getAccountByUsername($username) {
        global $pdo;
        $statement = $pdo->prepare("SELECT * FROM tbl_accounts WHERE username=:username LIMIT 0,1");
        $statement->bindValue(":username", $username);
        return ($statement->execute()) ? $statement->fetch() : NULL;
    }

    function createAuthToken($accountId) {
        $newToken = time() . $accountId;
        global $pdo;
        $statement = $pdo->prepare("INSERT INTO tbl_auth_tokens (token, accountId) VALUES (:token, :accountId)");
        $statement->bindValue(":token", $newToken);
        $statement->bindValue(":accountId", $accountId);
        return ($statement->execute()) ? $newToken : NULL;
    }

    function setTokenCookie($token) {
        global $sessionCookieName;
        $expireTime = time() + (86400 * 14); //14 day expiry
        setcookie($sessionCookieName, $token, $expireTime, '/');
    }

    function getAuthToken($token) {
        global $pdo;
        $statement = $pdo->prepare("SELECT * FROM tbl_auth_tokens WHERE token=:token LIMIT 0,1");
        $statement->bindValue(":token", $token);
        return ($statement->execute()) ? $statement->fetch() : NULL;
    }
?>