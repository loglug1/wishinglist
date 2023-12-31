<?php
    $year = Date('Y');

    $defaultTitle = "Wishinglist";
    $defaultHeader  = "<h1>Wishinglist</h1>";
    $defaultMainNav = "<a href='/' class='w3-bar-item w3-button w3-hover-theme'>Home</a><a href='/items' class='w3-bar-item w3-button w3-hover-theme'>Claimed Items</a><a href='/account' class='w3-bar-item w3-button w3-hover-theme'>Account</a>";
    $adminPanelButtons = (isAdmin()) ? "<a href='/admin' class='w3-bar-item w3-button w3-hover-theme'>Manage Users</a><a href='/adminItems' class='w3-bar-item w3-button w3-hover-theme'>Manage Items</a>" : '';
    $logoutButton = "<a href='/logout' class='w3-bar-item w3-button w3-right w3-hover-theme'>Logout</a>";
    $defaultNav = $defaultMainNav . $adminPanelButtons . $logoutButton;
    $defaultMain = "<p>Something went wrong :/</p>";
    $defaultFooter = "<p>Wishinglist <span class='w3-right'>&copy; {$year} Camden McKay</span></p>";

    $title = (function_exists('Title')) ? Title() : $defaultTitle;
    $nav = (function_exists('Nav')) ? Nav() : $defaultNav;
    $header = (function_exists('PageHeader')) ? PageHeader() : $defaultHeader;
    $main = (function_exists('Main')) ? Main() : $defaultMain;
    $footer = (function_exists('Footer')) ? Footer() : $defaultFooter;

    $message = (isset($_GET['m'])) ? htmlspecialchars($_GET['m']) : NULL;
    $messageBox = (isset($_GET['m'])) ? "<div class='w3-amber w3-panel w3-round-large'><h2>Message:</h2><p>{$message}</p></div>" : '';

    $warning = (isset($_GET['w'])) ? htmlspecialchars($_GET['w']) : NULL;
    $warningBox = (isset($_GET['w'])) ? "<div class='w3-red w3-panel w3-round-large'><h2>Warning:</h2><p>{$warning}</p></div>" : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="/theme.css">
    <title><?php echo $title; ?></title>
</head>
<body>
    <div class='w3-top' style='position: sticky;'>
    <header class='w3-container w3-theme'>
        <?php
        echo $header;
        ?>
    </header>
    <nav class='w3-bar w3-black'>
        <?php
        echo $nav;
        ?>
    </nav>
</div>
    <main class='w3-container w3-auto' style='margin-bottom: 50px;'>
        <?php
        echo $warningBox;
        echo $messageBox;
        echo $main;
        ?>
    </main>
    <footer class='w3-bottom w3-clear w3-container w3-black'>
        <?php echo $footer; ?>
    </footer>
</body>
</html>