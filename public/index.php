<?php
require_once __DIR__ . '/../include/functions.php';

$pageName = 'Home';

if (!isAuthenticated()) {
    redirectTo('/login');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['claim'])) {
        claimItem($_POST['itemId']);
        $message = urlencode("You've claimed an item. View it under Claimed Items.");
        redirectTo("/?m={$message}");
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
    $items = getUnclaimedItems();
    $itemsDiv = "<div class='w3-container'>";
    foreach ($items as $item) {
        $title = ($item['title'] != NULL) ? htmlspecialchars($item['title']) : '';
        $description = ($item['description'] != NULL) ? htmlspecialchars($item['description']) : '';
        $link = ($item['link'] != NULL) ? htmlspecialchars($item['link']) : '';
        $itemsDiv .= "<div class='w3-gray w3-panel w3-round-large'>
                        <h3>{$title}</h3>
                        <p>
                            {$description}<br>
                            <a href='{$link}'>{$link}</a>
                        </p>
                        <p>
                        <form method=POST action='/'>
                            <input type=hidden name=claim value=1>
                            <input type=hidden name=itemId value={$item['id']}>
                            <input type=submit name=submit value='Claim Item' class='w3-button w3-round-large w3-dark-gray w3-hover-black'>
                        </form>
                        </p>
                    </div>";
    }
    $itemsDiv .= '</div>';
    return "<h2>I want these things!</h2> {$itemsDiv}";
}

include __DIR__ . '/../include/page-template.php';
?>