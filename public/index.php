<?php
session_start();
require_once __DIR__ . '/../include/auth.php';
require_once __DIR__ . '/../include/pages.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php getTitle(); ?></title>
</head>
<body>
    <header><?php getHeader(); ?></header>
    <main><?php getContent(); ?></main>
    <footer><?php getFooter(); ?></footer>
</body>
</html>