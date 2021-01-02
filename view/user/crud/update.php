<?php

namespace Anax\View;

/**
 * View to create a new book.
 */

// Gather incoming variables and use default values if not set
$item = isset($item) ? $item : null;

// Create urls for navigation
$urlToView = url("topic");

?><h1>Update an item</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">View all</a>
</p>
