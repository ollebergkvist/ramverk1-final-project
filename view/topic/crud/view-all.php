<?php

namespace Anax\View;

/**
 * View to display all books.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("topic/create");
$urlToCategories = url("forum");



?><h1 class="text-center">Topics</h1>

<p>
    <a class="btn btn-secondary" href="<?= $urlToCategories ?>"><i
            class="fas fa-hand-point-left"></i> Categories</a>
    <a class="btn btn-secondary" href="<?= $urlToCreate ?>">Start a
        discussion</a>
    <!-- <a href="<?= $urlToDelete ?>">Delete</a> -->
</p>

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
            <th>Author</th>
            <th>Tags</th>
            <th>Posts</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
        <tr>
            <td>
                <a
                    href="<?= url("topic/view/{$item["topic"]->id}"); ?>"><?= $item["topic"]->subject ?></a>
            </td>
            <td><?= $item["topic"]->date ?></td>
            <td><?= $item["topic"]->author ?></td>
            <td>
                <?php foreach ($item["tags"] as $tag) : ?>
                <a class="btn btn-secondary btn-sm"
                    href="<?= url("tag/view/{$tag->id}"); ?>"><?= $tag->name ?></a>
                <?php endforeach; ?>
            </td>
            <td><i class="far fa-comment"></i> <?= $item["posts"]->key  ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>