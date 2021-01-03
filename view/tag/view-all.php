<?php

namespace Anax\View;

/**
 * View to display all categories.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

?><h1>Tags</h1>

<?php if (!$items) : ?>
<p>There are no items to show.</p>
<?php
    return;
endif;
?>

<div>
    <?php foreach ($items as $item) : ?>
    <a class="btn btn-secondary"
        href="<?= url("tag/view/{$item->id}"); ?>"><?= $item->name ?></a>
    <?php endforeach; ?>
</div>