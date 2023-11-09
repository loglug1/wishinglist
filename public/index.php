<?php
session_start();
require_once __DIR__ . '/../include/user.php';
require_once __DIR__ . '/../include/pages.php';

$jsonFileContents = file_get_contents(__DIR__ . '/../pages/index.json');
$pages = json_decode($jsonFileContents, true);

$pageId = $_SESSION['pageId'] ?? 'login';
$page = $pages[$pageId] ?? 'notfound';
$accountId = $_SESSION['accountId'] ?? NULL;
$profile = $_SESSION['profile'] ?? array('priviledge' => 0);
$params = $_SESSION['params'] ?? NULL;

require_once __DIR__ . '/../include/actions.php';
?>
<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
    <title><?php getTitle(); ?></title>
    <!--<style>
        * {
            border: 3px solid black;
        }
        header, footer, main {
            border: 3px solid blue;
        }
    </style>-->
</head>
<body>
    <header><?php getHeader(); ?></header>
    <main><?php getContent(); ?></main>
    <footer><?php getFooter(); ?></footer>
</body>
</html>