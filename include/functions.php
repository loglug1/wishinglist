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

    function isAdmin() {
        if (isAuthenticated()) {
            return getUserById($_SESSION['accountId'])['priviledge'] > 0;
        } else {
            return false;
        }
    }

    function isSuperAdmin() {
        return getUserById($_SESSION['accountId'])['priviledge'] > 1;
    }

    function logout() {
        global $sessionCookieName;
        if (isAuthenticated()) {
            $token = $_COOKIE[$sessionCookieName];
            destroyAuthToken($token);
            deleteTokenCookie();
            session_destroy();
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

    function getAccountById($id) {
        global $pdo;
        $statement = $pdo->prepare("SELECT * FROM tbl_accounts WHERE id=:id LIMIT 0,1");
        $statement->bindValue(":id", $id);
        return ($statement->execute()) ? $statement->fetch() : NULL;
    }

    function getUserById($id) {
        global $pdo;
        $statement = $pdo->prepare("SELECT tbl_accounts.id, tbl_accounts.username, tbl_profiles.firstName, tbl_profiles.lastName, tbl_profiles.priviledge FROM tbl_accounts JOIN tbl_profiles ON tbl_accounts.id=tbl_profiles.accountId WHERE tbl_profiles.id=:id LIMIT 0,1");
        $statement->bindValue(":id", $id);
        return ($statement->execute()) ? $statement->fetch() : NULL;
    }

    function getAllUsers() {
        global $pdo;
        $statement = $pdo->prepare("SELECT tbl_accounts.id, tbl_accounts.username, tbl_profiles.firstName, tbl_profiles.lastName, tbl_profiles.priviledge FROM tbl_accounts JOIN tbl_profiles ON tbl_accounts.id=tbl_profiles.accountId");
        return ($statement->execute()) ? $statement->fetchAll() : NULL;
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

    function deleteTokenCookie() {
        global $sessionCookieName;
        setcookie($sessionCookieName, '', 1, '/');
    }

    function getAuthToken($token) {
        global $pdo;
        $statement = $pdo->prepare("SELECT * FROM tbl_auth_tokens WHERE token=:token LIMIT 0,1");
        $statement->bindValue(":token", $token);
        return ($statement->execute()) ? $statement->fetch() : NULL;
    }

    function destroyAuthToken($token) {
        global $pdo;
        $statement = $pdo->prepare("DELETE FROM tbl_auth_tokens WHERE token=:token;");
        $statement->bindValue(':token', $token);
        if (!$statement->execute()) {
            die('db error');
        }
    }

    function destroyUserAuthTokens($accountId) {
        global $pdo;
        $statement = $pdo->prepare("DELETE FROM tbl_auth_tokens WHERE accountId=:accountId;");
        $statement->bindValue(':accountId', $accountId);
        if (!$statement->execute()) {
            die('db error');
        }
    }

    function createProfile($accountId, $firstName, $lastName, $priviledge) {
        global $pdo;
        $profile = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'priviledge' => $priviledge,
            'accountId' => $accountId
        ];
        $statement = $pdo->prepare("INSERT INTO tbl_profiles (firstName, lastName, priviledge, accountId) VALUES (:firstName, :lastName, :priviledge, :accountId);");
        if (!$statement->execute($profile)) {
            die('db error');
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
            die('db error');
        }
        $newAccount = getAccountByUsername($username);
        if($newAccount != NULL) {
            return $newAccount['id'];
        } else {
            die('db error');
        }
    }

    function updateAccount($accountId, $username, $password) {
        global $pdo;
        $account = [
            'accountId' => $accountId,
            'username' => $username,
        ];
        if ($password == '') {
            $statement = $pdo->prepare("UPDATE tbl_accounts SET username=:username WHERE id=:accountId;");
        } else {
            $account['password'] = password_hash($password, PASSWORD_BCRYPT);
            $statement = $pdo->prepare("UPDATE tbl_accounts SET username=:username, password=:password WHERE id=:accountId;");
        }
        if (!$statement->execute($account)) {
            die('db error');
        }
    }

    function updateProfile($accountId, $firstName, $lastName, $priviledge) {
        global $pdo;
        $profile = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'priviledge' => $priviledge,
            'accountId' => $accountId
        ];
        $statement = $pdo->prepare("UPDATE tbl_profiles SET firstName=:firstName, lastName=:lastName, priviledge=:priviledge WHERE accountId=:accountId;");
        if (!$statement->execute($profile)) {
            die('db error');
        }
    }

    function deleteAccount($accountId) {
        global $pdo;
        $statement = $pdo->prepare("DELETE FROM tbl_accounts WHERE id=:accountId;");
        $statement->bindValue(':accountId', $accountId);
        if (!$statement->execute()) {
            die('db error');
        }
    }

    function deleteProfile($accountId) {
        global $pdo;
        $statement = $pdo->prepare("DELETE FROM tbl_profiles WHERE id=:accountId;");
        $statement->bindValue(':accountId', $accountId);
        if (!$statement->execute()) {
            die('db error');
        }
    }

    function getUnclaimedItems() {
        global $pdo;
        $statement = $pdo->prepare("SELECT id, title, description, link FROM tbl_items WHERE claimedBy IS NULL");
        return ($statement->execute()) ? $statement->fetchAll() : NULL;
    }

    function getClaimedItems($accountId) {
        global $pdo;
        $statement = $pdo->prepare("SELECT id, title, description, link FROM tbl_items WHERE claimedBy=:accountId");
        $statement->bindValue(':accountId', $accountId);
        return ($statement->execute()) ? $statement->fetchAll() : NULL;
    }

    function getAllItems() {
        global $pdo;
        $statement = $pdo->prepare("SELECT id, title, description, link, claimedBy FROM tbl_items");
        return ($statement->execute()) ? $statement->fetchAll() : NULL;
    }

    function deleteItem($id) {
        global $pdo;
        $statement = $pdo->prepare("DELETE FROM tbl_items WHERE id=:id;");
        $statement->bindValue(':id', $id);
        if (!$statement->execute()) {
            die('db error');
        }
    }
    
    function updateItem($id, $title, $description, $link) {
        global $pdo;
        if (!str_starts_with($link, 'https://') && !str_starts_with($link, 'http://') && $link != '') {
            $link = 'http://' . $link;
        }
        $profile = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'link' => $link
        ];
        $statement = $pdo->prepare("UPDATE tbl_items SET title=:title, description=:description, link=:link WHERE id=:id;");
        if (!$statement->execute($profile)) {
            die('db error');
        }
    }

    function createItem($title, $description, $link) {
        global $pdo;
        if (!str_starts_with($link, 'https://') && !str_starts_with($link, 'http://') && $link != '') {
            $link = 'http://' . $link;
        }
        $profile = [
            'title' => $title,
            'description' => $description,
            'link' => $link
        ];
        $statement = $pdo->prepare("INSERT INTO tbl_items (title, description, link) VALUES (:title, :description, :link);");
        if (!$statement->execute($profile)) {
            die('db error');
        }
    }

    function getItemById($id) {
        global $pdo;
        $statement = $pdo->prepare("SELECT title, description, link FROM tbl_items WHERE id=:id LIMIT 0,1");
        $statement->bindValue(":id", $id);
        return ($statement->execute()) ? $statement->fetch() : NULL;
    }

    function claimItem($id) {
        global $pdo;
        $values = [
            'id' => $id,
            'claimedBy' => $_SESSION['accountId']
        ];
        $statement = $pdo->prepare("UPDATE tbl_items SET claimedBy=:claimedBy WHERE id=:id");
        if (!$statement->execute($values)) {
            die('db error');
        }
    }

    function unclaimItem($id) {
        global $pdo;
        $statement = $pdo->prepare("UPDATE tbl_items SET claimedBy=:claimedBy WHERE id=:id");
        $statement->bindValue(':claimedBy', NULL);
        $statement->bindValue(':id', $id);
        if (!$statement->execute()) {
            die('db error');
        }
    }

    function unclaimAllItems($userId) {
        global $pdo;
        $statement = $pdo->prepare("UPDATE tbl_items SET claimedBy=:newClaimedBy WHERE claimedBy=:currentlyClaimedBy");
        $statement->bindValue(':newClaimedBy', NULL);
        $statement->bindValue(':currentlyClaimedBy', $userId);
        if (!$statement->execute()) {
            die('db error');
        }
    }

    function deleteUser($id) {
        destroyUserAuthTokens($id);
        unclaimAllItems($id);
        deleteProfile($id);
        deleteAccount($id);
    }
?>