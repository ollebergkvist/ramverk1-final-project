<?php

namespace Anax\View;

/**
 * View to create a new post.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToViewItems = url("post");

?><h1 class="text-center">Reply</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToViewItems ?>">View all</a>
</p>