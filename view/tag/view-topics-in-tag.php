<?php

namespace Anax\View;

/**
 * View to display all categories.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;
$tagName = isset($items[0]["tagName"]) ? $items[0]["tagName"] : null;

?><h1 class="text-center"><?= $tagName ?></h1>

<?php if (!$items) : ?>
<p>There are no items to show.</p>
<?php
    return;
endif;
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Topic</th>
            <th>Date</th>
            <th>Author</th>
            <th>Gravatar</th>
            <!-- <th>Tags</th> -->
            <th>Posts</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
        <tr>
            <td>
                <a
                    href="<?= url("topic/view/{$item["topic"]->id}"); ?>"><?= htmlentities($item["topic"]->subject) ?></a>
            </td>
            <td><?= htmlentities($item["topic"]->date) ?></td>
            <td><a
                    href="<?= url("user/show/{$item["topic"]->author}"); ?>"><?= htmlentities($item["topic"]->author) ?></a>
            </td>
            <td>
                <img src=" <?= htmlentities($item["gravatar"]) ?>">
            </td>
            <!-- <td>
                <?php foreach ($item["tags"] as $tag) : ?>
                <a class=" btn btn-secondary btn-sm"
                    href="<?= url("tag/view/{$tag->id}"); ?>"><?= htmlentities($tag->name) ?></a>
                <?php endforeach; ?>
            </td> -->
            <td><i class="far fa-comment"></i>
                <?= htmlentities($item["posts"]) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>