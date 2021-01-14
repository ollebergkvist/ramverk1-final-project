<?php

namespace Anax\View;

?>

<h1>Latest topics</h1>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Date</th>
            <th>Author</th>
            <th>Gravatar</th>
            <th>Tags</th>
            <th>Posts</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($popularTopics as $popularTopic) : ?>
        <tr>
            <td>
                <a
                    href="<?= url("topic/view/{$popularTopic["topic"]->id}"); ?>"><?= htmlentities($popularTopic["topic"]->subject) ?></a>
            </td>
            <td><?= htmlentities($popularTopic["topic"]->date) ?></td>
            <td><a
                    href="<?= url("user/show/{$popularTopic["topic"]->author}"); ?>"><?= htmlentities($popularTopic["topic"]->author) ?></a>
            </td>
            <td>
                <img src="<?= htmlentities($popularTopic["gravatar"]) ?>">
            </td>
            <td>
                <?php foreach ($popularTopic["tags"] as $tag) : ?>
                <a class=" btn btn-secondary btn-sm"
                    href="<?= url("tag/view/{$tag->id}"); ?>"><?= htmlentities($tag->name) ?></a>
                <?php endforeach; ?>
            </td>
            <td><i class="far fa-comment"></i>
                <?= htmlentities($popularTopic["posts"]) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h1>Most popular tags</h1>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Name</th>
            <th>Topics</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($popularTags as $popularTag): ?>
        <tr>
            <td><span class="btn btn-secondary"
                    href="<?= url("tag/view/{$popularTag->id}"); ?>"><?= htmlentities($popularTag->name) ?></a>
            </td>
            <td>
                <?= htmlentities($popularTag->count) ?>
            </td>
        </tr>
        <?php endforeach; ?>

    <tbody>
</table>

<h1>Most active users</h1>
<table class="table table-hover">
    <thead>
        <tr>
            <th>Username</th>
            <th>Gravatar</th>
            <th>Points</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mostActiveUsers as $mostActiveUser): ?>
        <tr>
            <td><a
                    href="<?=url("user/view/" . $mostActiveUser["user"]->username)?>"><?= htmlentities($mostActiveUser["user"]->username) ?></a>
            </td>
            <td><img src="<?= htmlentities($mostActiveUser["gravatar"]) ?>">
            </td>
            <!-- <td><span><?= htmlentities($mostActiveUser["user"]->points) ?></span></td> -->
        </tr>
        <?php endforeach;?>
    </tbody>
</table>