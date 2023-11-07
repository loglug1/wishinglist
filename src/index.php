<?php
$dbHost = getenv('AZURE_MYSQL_HOST') ?: 'localhost';
$dbDatabase = getenv('AZURE_MYSQL_DBNAME') ?: 'project';
$dbUsername = getenv('AZURE_MYSQL_USERNAME') ?: 'root';
$dbPassword = getenv('AZURE_MYSQL_PASSWORD') ?: 'root';
$dbPort = getenv('AZURE_MYSQL_PORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};port={$dbPort}", $dbUsername, $dbPassword, array(PDO::ATTR_PERSISTENT => true));
} catch (PDOException $e) {
    var_dump($e);
    die();
}
echo "Hello World! 2";
?>