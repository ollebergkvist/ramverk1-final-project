<?php

namespace Anax\View;

/**
 * View to create a new post.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$item = isset($item) ? $item : null;

// Create urls for navigation
$urlToView = url("admin");



?><h1>Update an item</h1>

<?= $form ?>

<p>
    <a href="<?= $urlToView ?>">Return to admin dashboard</a>
</p>