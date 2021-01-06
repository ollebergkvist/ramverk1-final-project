<?php

namespace Anax\View;

/**
 * View to display all categories.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

?><h1 class="text-center"><?= $items[0]["tagName"] ?></h1>

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
                    href="<?= url("topic/view/{$item["topic"]->id}"); ?>"><?= $item["topic"]->subject ?></a>
            </td>
            <td><?= $item["topic"]->date ?></td>
            <td><a
                    href="<?= url("user/show/{$item["topic"]->author}"); ?>"><?= $item["topic"]->author ?></a>
            </td>
            <td>
                <img src=" <?=$item["gravatar"] ?>">
            </td>
            <!-- <td>
                <?php foreach ($item["tags"] as $tag) : ?>
                <a class=" btn btn-secondary btn-sm"
                    href="<?= url("tag/view/{$tag->id}"); ?>"><?= $tag->name ?></a>
                <?php endforeach; ?>
            </td> -->
            <td><i class="far fa-comment"></i> <?= $item["posts"] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>