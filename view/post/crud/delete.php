<?php

namespace Anax\View;

/**
 * View to create a new post.
 */

 // Create urls for navigation
$urlToView = url("admin");

?><h1>Delete a post</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">Return to admin dashboard</a>
</p>