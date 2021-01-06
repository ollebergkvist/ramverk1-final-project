<?php

namespace Anax\View;

/**
 * View to create a new post.
 */

 // Gather incoming variables and use default values if not set
$item = isset($item) ? $item : null;

// Create urls for navigation
$urlToView = url("admin");

?><h1>Update a category</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">Return to admin dashboard</a>
</p>