<?php

namespace Anax\View;

/**
 * View to create a new book.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToViewItems = url("topic");



?><h1 class='text-center'>Create topic</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToViewItems ?>">View all</a>
</p>