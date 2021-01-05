<?php

namespace Anax\View;

/**
 * View to create a new post.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Create urls for navigation
$urlToView = url("admin");



?><h1>Delete a category</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">Return to admin dashboard</a>
</p>