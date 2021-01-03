<?php

namespace Anax\View;

/**
 * View to display all categories.
 */

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