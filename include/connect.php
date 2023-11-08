<?php
$dbHost = getenv('AZURE_MYSQL_HOST') ?: 'localhost';
$dbName = getenv('AZURE_MYSQL_DBNAME') ?: 'project';
$dbUsername = getenv('AZURE_MYSQL_USERNAME') ?: 'root';
$dbPassword = getenv('AZURE_MYSQL_PASSWORD') ?: 'root';
$dbPort = getenv('AZURE_MYSQL_PORT') ?: '3306';
$dbAttrs = array(PDO::ATTR_PERSISTENT => true);
if (getenv('MYSQL_ATTR_SSL_CA')) {
    $dbAttrs[PDO::MYSQL_ATTR_SSL_CA] = getenv('MYSQL_ATTR_SSL_CA');
    $dbAttrs[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
}

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};port={$dbPort}", $dbUsername, $dbPassword, $dbAttrs);
} catch (PDOException $e) {
    gotoPage($page, "?message=dberr");
}

$pdo->query("CREATE TABLE IF NOT EXISTS tbl_accounts (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
);");

$pdo->query("CREATE TABLE IF NOT EXISTS tbl_profiles (
    id INT AUTO_INCREMENT,
    priviledge INT NOT NULL,
    firstName VARCHAR(255),
    lastName VARCHAR(255),
    accountId INT,
    PRIMARY KEY(id),
    FOREIGN KEY (accountId) REFERENCES tbl_accounts(id)
);");

$defaultPassword = getenv('DEFAULT_WEB_PASSWORD') ?: 'password';
$defaultAccount = [
    'id' => -1,
    'username' => 'admin',
    'password' => password_hash($defaultPassword,PASSWORD_BCRYPT)
];
$defaultProfile = [
    'id' => -1,
    'priviledge' => 2,
    'accountId' => -1
];

$statement = $pdo->prepare("INSERT IGNORE INTO tbl_accounts (id, username, password) VALUES (:id, :username, :password);");
if (!$statement->execute($defaultAccount)) {
    die("error");
}
$statement = $pdo->prepare("INSERT IGNORE INTO tbl_profiles (id, priviledge, accountId) VALUES (:id, :priviledge, :accountId);");
if (!$statement->execute($defaultProfile)) {
    die("error");
}
?>