<?php

namespace Anax\View;

/**
 * View to display all posts.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("post/create");
$urlToDelete = url("post/delete");

?><h1>View all items</h1>

<p>
    <a href="<?= $urlToCreate ?>">Create</a> |
    <a href="<?= $urlToDelete ?>">Delete</a>
</p>

<?php if (!$items) : ?>
<p>There are no items to show.</p>
<?php
    return;
endif;
?>

<table>
    <tr>
        <th>ID</th>
        <th>Content</th>
        <th>Date</th>
        <th>Topic</th>
        <th>Author</th>
    </tr>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td>
            <a
                href="<?= url("post/update/{$item->id}"); ?>"><?= $item->id ?></a>
        </td>
        <td><?= $item->content ?></td>
        <td><?= $item->date ?></td>
        <td><?= $item->topic ?></td>
        <td><?= $item->author ?></td>
    </tr>
    <?php endforeach; ?>
</table>