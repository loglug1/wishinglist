<?php
require_once __DIR__ . '/../../include/functions.php';

$pageName = 'Your Claimed Items';

if (!isAuthenticated()) {
    redirectTo('/login');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['unclaim'])) {
        unclaimItem($_POST['itemId']);
        $message = urlencode("Item successfully returned to home page.");
        redirectTo("/items?m={$message}");
    }
}

function Title() {
    global $pageName;
    return $pageName;
}

function PageHeader() {
    global $pageName;
    return "<h1>{$pageName}</h1>";
}

function Main() {
    global $pageName;
    $items = getClaimedItems($_SESSION['accountId']);
    $itemsDiv = "<div class='w3-container'>";
    foreach ($items as $item) {
        $title = ($item['title'] != NULL) ? htmlspecialchars($item['title']) : '';
        $description = ($item['description'] != NULL) ? htmlspecialchars($item['description']) : '';
        $link = ($item['link'] != NULL) ? htmlspecialchars($item['link']) : '';
        $shortenedLink = substr($link, 0, 50);
        $shortenedLink = ($shortenedLink != $link) ? $shortenedLink . '...' : $shortenedLink;
        $itemsDiv .= "<div class='w3-gray w3-panel w3-round-large'>
                        <h3>{$title}</h3>
                        <p>
                            {$description}<br>
                            <a href='{$link}' target='_blank' style='word-wrap: break-word;'>{$shortenedLink}</a>
                        </p>
                        <p>
                        <form method=POST action='/items/'>
                            <input type=hidden name=unclaim value=1>
                            <input type=hidden name=itemId value={$item['id']}>
                            <input type=submit name=submit value='Unclaim Item' class='w3-button w3-round-large w3-dark-gray w3-hover-black'>
                        </form>
                        </p>
                    </div>";
    }
    $itemsDiv .= '</div>';
    return "<h2>Items you have claimed to buy:</h2> {$itemsDiv}";
}

include __DIR__ . '/../../include/page-template.php';
?>