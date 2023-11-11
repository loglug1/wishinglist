<?php
    $year = Date('Y');

    $defaultTitle = "Wishinglist";
    $defaultHeader  = "<h1>Wishinglist</h1>";
    $defaultMainNav = "<a href='/' class='w3-bar-item w3-button'>Home</a><a href='/account' class='w3-bar-item w3-button'>Account</a>";
    $adminPanelButtons = (isAdmin()) ? "<a href='/admin' class='w3-bar-item w3-button'>Manage Users</a><a href='/adminItems' class='w3-bar-item w3-button'>Manage Items</a>" : '';
    $logoutButton = "<a href='/logout' class='w3-bar-item w3-button w3-right'>Logout</a>";
    $defaultNav = $defaultMainNav . $adminPanelButtons . $logoutButton;
    $defaultMain = "<p>Something went wrong :/</p>";
    $defaultFooter = "<p>&copy; {$year} Camden McKay</p>";

    $title = (function_exists('Title')) ? Title() : $defaultTitle;
    $nav = (function_exists('Nav')) ? Nav() : $defaultNav;
    $header = (function_exists('PageHeader')) ? PageHeader() : $defaultHeader;
    $main = (function_exists('Main')) ? Main() : $defaultMain;
    $footer = (function_exists('Footer')) ? Footer() : $defaultFooter;

    $message = (isset($_GET['m'])) ? htmlspecialchars($_GET['m']) : NULL;
    $messageBox = (isset($_GET['m'])) ? "<div class='w3-yellow w3-panel w3-border'><h2>Message:</h2><p>{$message}</p></div>" : '';

    $warning = (isset($_GET['w'])) ? htmlspecialchars($_GET['w']) : NULL;
    $warningBox = (isset($_GET['w'])) ? "<div class='w3-red w3-panel w3-border'><h2>Warning:</h2><p>{$warning}</p></div>" : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
    <title><?php echo $title; ?></title>
</head>
<body>
    <header class='w3-container w3-black'>
        <?php
        echo $header;
        ?>
    </header>
    <nav class='w3-bar w3-black'>
        <?php
        echo $nav;
        ?>
    </nav>
    <main class='w3-container w3-auto'>
        <?php
        echo $warningBox;
        echo $messageBox;
        echo $main;
        ?>
    </main>
    <footer class='w3-bottom w3-container w3-black'>
        <?php echo $footer; ?>
    </footer>
</body>
</html>