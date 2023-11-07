<?php
$dbHost = getenv('AZURE_MYSQL_HOST') ?: 'localhost';
$dbDatabase = getenv('AZURE_MYSQL_DBNAME') ?: 'project';
$dbUsername = getenv('AZURE_MYSQL_USERNAME') ?: 'root';
$dbPassword = getenv('AZURE_MYSQL_PASSWORD') ?: 'root';
$dbPort = getenv('AZURE_MYSQL_PORT') ?: '3306';
$dbAttrs = [
    PDO::ATTR_PERSISTENT => true;
];
if (getenv('MYSQL_ATTR_SSL_CA')) {
    $dbAttrs[PDO::MYSQL_ATTR_SSL_CA] = getenv('MYSQL_ATTR_SSL_CA');
    $dbAttrs[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
}

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};port={$dbPort}", $dbUsername, $dbPassword, $dbAttrs);
} catch (PDOException $e) {
    die($e->fetchMessage());
}
echo "Hello World! 2";
?>