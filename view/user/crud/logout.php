<?php

namespace Anax\View;

/**
 * View to create a new book.
 */
// Show all incoming variables/functions

// Create urls for navigation
$urlToView =  $_SESSION["permission"] === "user" ? url("user") : url("admin");

?><h1>Logout</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">Go back</a>
</p>