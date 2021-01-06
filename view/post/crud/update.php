<?php

namespace Anax\View;

/**
 * View to create a new post.
 */

 // Gather incoming variables and use default values if not set
$item = isset($item) ? $item : null;

// Create urls for navigation
$urlToView = url("post");

?><h1>Update a post</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">View all</a>
</p>