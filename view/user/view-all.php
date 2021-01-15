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
$author = isset($items[0]["author"]) ? $items[0]["author"] : null;

?>
<h1 class="text-center"><?= $author ?> Activity </h1>
<h4>Level: <?= $level ?></h4>
<h4>Points: <?= $score ?></h4>
<h4>Total Votes: <?= $score ?></h4>

<h1 class="text-center">Topics</h1>

<?php if (!$items) : ?>
<p>There are no topics to show.</p>
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
                    href="<?= url("topic/view/{$item["topic"]->id}"); ?>"><?= htmlentities($item["topic"]->subject) ?></a>
            </td>
            <td><?= htmlentities($item["topic"]->date) ?></td>
            <td>
                <?php foreach ($item["tags"] as $tag) : ?>
                <a class="btn btn-secondary btn-sm"
                    href="<?= url("tag/view/{$tag->tag}"); ?>"><?= $tag->name ?></a>
                <?php endforeach; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h1 class="text-center">Replies</h1>

<?php if (!$items2) : ?>
<p>There are no replies to show.</p>
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
                    href="<?= url("topic/view/{$item["topic"]->id}"); ?>"><?= htmlentities($item["topic"]->subject) ?></a>
            </td>
            <td><?= htmlentities($item["topic"]->date) ?></td>
            <td>
                <?php foreach ($item["tags"] as $tag) : ?>
                <a class="btn btn-secondary btn-sm"
                    href="<?= url("tag/view/{$tag->tag}"); ?>"><?= $tag->name ?></a>
                <?php endforeach; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>