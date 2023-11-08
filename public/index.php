<?php
session_start();
require_once __DIR__ . '/../include/user.php';
require_once __DIR__ . '/../include/pages.php';

$page = $_SESSION['page'] ?? 'login';
$accountId = $_SESSION['accountId'] ?? NULL;
$profile = $_SESSION['profile'] ?? array('priviledge' => 0);

require_once __DIR__ . '/../include/actions.php';
?>
<!DOCTYPE html>
<html lang=en>
<head>
    <meta charset=UTF-8>
    <meta name=viewport content="width=device-width, initial-scale=1.0">
    <title><?php getTitle(); ?></title>
    <style>
        * {
            border: 3px solid black;
        }
        header, footer, main {
            border: 3px solid blue;
        }
    </style>
</head>
<body>
    <header><?php getHeader(); ?></header>
    <main><?php getContent(); ?></main>
    <footer><?php getFooter(); ?></footer>
</body>
</html>