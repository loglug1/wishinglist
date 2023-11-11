<?php
    $year = Date('Y');

    $defaultHeader  = "<h1>Wishinglist</h1>";
    $defaultMain = "<p>Something went wrong :/</p>";
    $defaultFooter = "<p>&copy; {$year} Camden McKay";

    $header = (function_exists('PageHeader')) ? PageHeader() : $defaultHeader;
    $main = (function_exists('Main')) ? Main() : $defaultMain;
    $footer = (function_exists('Footer')) ? Footer() : $defaultFooter;

    $message = (isset($_GET['m'])) ? htmlspecialchars($_GET['m']) : NULL;
    $messageBox = "<h2>{$message}</h2>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Title(); ?></title>
</head>
<body>
    <header>
        <?php
        echo $header; 
        echo $messageBox;
        ?>
    </header>
    <main>
        <?php echo $main; ?>
    </main>
    <footer>
        <?php echo $footer; ?>
    </footer>
</body>
</html>