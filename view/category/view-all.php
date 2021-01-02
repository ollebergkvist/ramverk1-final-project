<?php

namespace Anax\View;

/**
 * View to display all categories.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

?><h1 class="text-center">Code Forum</h1>

<?php if (!$items) : ?>
<p>There are no items to show.</p>
<?php
    return;
endif;
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Topics</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
        <tr>
            <td>
                <a
                    href="<?= url("forum/view/{$item->id}"); ?>"><?= $item->name ?></a>
            </td>
            <td><?= $item->description ?></td>
            <td><i class="far fa-comment"></i> <?= $item->topics ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>