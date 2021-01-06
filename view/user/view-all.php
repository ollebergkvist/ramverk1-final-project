<?php

namespace Anax\View;

/**
 * View to display all topics by user.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("topic/create");
$urlToCategories = url("forum");
$author = $items[0]["author"];

?><h1 class="text-center">Topics started by <?= $author ?></h1>

<?php if (!$items) : ?>
<p>There are no items to show.</p>
<?php
    return;
endif;
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Date</th>
            <th>Tags</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
        <tr>
            <td>
                <a
                    href="<?= url("topic/view/{$item["topic"]->id}"); ?>"><?= $item["topic"]->subject?></a>
            </td>
            <td><?= $item["topic"]->date ?></td>
            <td>
                <?php foreach ($item["tags"] as $tag) : ?>
                <a class="btn btn-secondary btn-sm"
                    href="<?= url("tag/view/{$tag->id}"); ?>"><?= $tag->name ?></a>
                <?php endforeach; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>