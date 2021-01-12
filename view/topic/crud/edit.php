<?php

namespace Anax\View;

/**
 * View to display all categories.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

?><h1>Select topic to edit</h1>

<?php if (!$items) : ?>
<p>There are no items to show.</p>
<?php
    return;
endif;
?>

<div>
    <?php foreach ($items as $item) : ?>
    <a class="btn btn-secondary"
        href="<?= url("topic/update/{$item->id}"); ?>"><?= $item->subject ?></a>
    <?php endforeach; ?>
</div>