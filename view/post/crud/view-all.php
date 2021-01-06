<?php

namespace Anax\View;

use Olbe19\Filter\Markdown;

$markdown = new Markdown();

/**
 * View to display all posts.
 */
// Show all incoming variables/functions
//var_dump(get_defined_functions());
//echo showEnvironment(get_defined_vars());

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("post/create");
$urlToTopics = url("topic");

?><h1 class="text-center">Post</h1>

<p>
    <a class="btn btn-secondary" href="<?= $urlToTopics ?>"><i
            class="fas fa-hand-point-left"></i> Topics</a>
</p>

<?php if (!$items) : ?>
<p>There are no items to show.</p>
<?php
    return;
endif;
?>

<table class="table">
    <?php foreach ($items as $item) : ?>
    <tr>
        <!-- <td>
            <a href="<?= url("post/update/{$item->id}"); ?>"><?= $item->id ?></a>
        </td> -->
        <td><?= $markdown->markdown($item->content); ?></td>
        <td><?= $item->date ?></td>
        <td><?= $item->author ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<p>
    <a class="" href="<?= $urlToCreate ?>">Reply</a>
</p>