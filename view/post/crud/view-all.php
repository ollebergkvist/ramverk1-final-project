<?php

namespace Anax\View;

use Olbe19\Filter\Markdown;

$markdown = new Markdown();
$session = $this->di->get("session");
$username = $session->get("username");
$disabled = $username ? null : true;
$upvoteBtn = false;
$downvoteBtn = false;
$upVoteBtnColor = false;
$downVoteBtnColor = false;


/**
 * View to display all posts.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("post/create");
$urlToTopics = url("topic");
$urlToPostAccept = url("post/accept");
$urlToVote = url("post/vote");

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
    <thead>
        <th>Content</th>
        <th>Date</th>
        <th>Author</th>
        <th>Up vote</th>
        <th>Down vote</th>
        <th>Accept</th>
    </thead>
    <?php foreach ($items as $item) : ?>
    <tr>
        <td><?= $markdown->markdown(htmlentities($item->content)); ?></td>
        <td><?= htmlentities($item->date) ?></td>
        <td><?= htmlentities($item->author) ?></td>
        <td <?= $upVoteBtnColor ?>>
            <form action=<?= $urlToVote ?> method="get">
                <input hidden name="username" value="<?= $username ?>">
                <input hidden name="postID" value="<?= $item->id ?>">
                <button class="btn btn-block" type="submit" name="vote"
                    value="up-vote" <?= $disabled ?>>
                    <i class="far fa-arrow-alt-circle-up"></i>
                </button>
            </form>
        </td>
        <td <?= $downVoteBtnColor ?>>
            <form action=<?= $urlToVote ?> method="get">
                <input hidden name="username" value="<?= $username ?>">
                <input hidden name="postID" value="<?= $item->id ?>">
                <button class="btn btn-block" type="submit" name="vote"
                    value="down-vote" <?= $disabled ?>>
                    <i class="far fa-arrow-alt-circle-down"></i>
                </button>
            </form>
        </td>
        <td>
            <?php if ($item->accepted == 1): ?>
            <button class="btn btn-block shadow-none"><i
                    class="far fa-check-square text-primary"></i></button>
            <?php elseif ($username == $item->author): ?>
            <form action=<?= $urlToPostAccept ?> method="get">
                <input hidden name="username" value="<?= $username ?>">
                <input hidden name="postID" value="<?= $item->id ?>">
                <button class="btn btn-block" type="submit" <?= $disabled ?>>
                    <i class="far fa-check-square"></i>
                </button>
            </form>
            <?php endif;?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<p>
    <a class="" href="<?= $urlToCreate ?>">Reply</a>
</p>