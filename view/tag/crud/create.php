<?php

namespace Anax\View;

/**
 * View to create a new post.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToViewItems = url("admin");

?><h1 class="text-center">Create tag</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToViewItems ?>">Return to admin dashboard</a>
</p>