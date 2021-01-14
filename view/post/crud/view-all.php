<?php

namespace Anax\View;


use Olbe19\Vote\Vote;
use Olbe19\Vote2Topic\Vote2Topic;
use Olbe19\Filter\Markdown;

/**
 * View to display all posts.
 */

// Gather incoming variables and use default values if not set
$items = isset($items) ? $items : null;

// Create urls for navigation
$urlToCreate = url("post/create");
$urlToTopics = url("topic");
$urlToPostAccept = url("post/accept");
$urlToVotePost = url("post/vote");
$urlToVoteTopic = url("topic/vote");

$markdown = new Markdown();
$vote = new Vote();
$vote->setDb($di->get("dbqb"));
$vote2topic = new Vote2Topic();
$vote2topic->setDb($di->get("dbqb"));
$session = $this->di->get("session");
$username = $session->get("username");

$disabled = $username ? null : true;
$upvoteBtn = false;
$downvoteBtn = false;
$successColor = false;
$dangerColor = false;
$successColorPost = false;
$dangerColorPost = false;

if ($username) {
    $getVote = $vote2topic->getVote($topic->id, $username);
    if ($getVote == "up-vote") {
        $successColor = "text-success";
    } else if ($getVote == "down-vote") {
        $dangerColor = "text-danger";
    }
}

?><h1 class="text-center">Topic</h1>

<p>
    <a class="btn btn-secondary" href="<?= $urlToTopics ?>"><i
            class="fas fa-hand-point-left"></i> Topics</a>
</p>

<table class="table">
    <thead>
        <th>Content</th>
        <th>Date</th>
        <th>Author</th>
        <th>Rank</th>
        <?php if ($username !== $topic->author): ?>
        <th>Up vote</th>
        <th>Down vote</th>
        <?php endif;?>
    </thead>
    <tr>
        <td><?= $markdown->markdown(htmlentities($topic->content)); ?></td>
        <td><?= htmlentities($topic->date) ?></td>
        <td><?= htmlentities($topic->author) ?></td>
        <td><?= htmlentities($topic->rank) ?></td>
        <?php if ($username !== $topic->author): ?>
        <td>
            <form action=<?= $urlToVoteTopic ?> method="get">
                <input hidden name="username" value="<?= $username ?>">
                <input hidden name="topicID" value="<?= $topic->id ?>">
                <button class="btn btn-block" type="submit" name="vote"
                    value="up-vote" <?= $disabled ?>>
                    <i
                        class="far fa-arrow-alt-circle-up <?= $successColor ?>"></i>
                </button>
            </form>
        </td>
        <?php endif;?>
        <?php if ($username !== $topic->author): ?>
        <td>
            <form action=<?= $urlToVoteTopic ?> method="get">
                <input hidden name="username" value="<?= $username ?>">
                <input hidden name="topicID" value="<?= $topic->id ?>">
                <button class="btn btn-block" type="submit" name="vote"
                    value="down-vote" <?= $disabled ?>>
                    <i
                        class="far fa-arrow-alt-circle-down <?= $dangerColor ?>"></i>
                </button>
            </form>
        </td>
        <?php endif;?>
    </tr>
</table>

<?php if (!$items) : ?>
<p>There are no replies to this topic yet.</p>
<?php else : ?>

<h2 class="text-center">Replies</h2>
<h4>Order by</h4>
<form class="buttons" action=<?= url("post") ?> method="get">
    <button class="btn btn-secondary" type="submit" name="order" value="date">
        Date
    </button>
</form>
<form class="buttons" action=<?= url("post") ?> method="get">
    <button class="btn btn-secondary" type="submit" name="order" value="rank">
        Rank
    </button>
</form>

<table class="table">
    <thead>
        <th>Reply</th>
        <th>Date</th>
        <th>Author</th>
        <th>Rank</th>
        <th>Up vote</th>
        <th>Down vote</th>
        <th>Accept</th>
    </thead>
    <?php foreach ($items as $item) : ?>
    <?php
        $successColorPost = null;
        $dangerColorPost = null;
        $vote = new Vote();
        $vote->setDb($di->get("dbqb"));
        if ($username) {
            $getVote = $vote->getVote($item->id, $username);
            if ($getVote == "up-vote") {
                $successColorPost = "text-success";
            } else if ($getVote == "down-vote") {
                $dangerColorPost = "text-danger";
            }
        }
    ?>
    <tr>
        <td><?= $markdown->markdown(htmlentities($item->content)); ?></td>
        <td><?= htmlentities($item->date) ?></td>
        <td><?= htmlentities($item->author) ?></td>
        <td><?= htmlentities($item->rank) ?></td>
        <td>
            <form action=<?= $urlToVotePost ?> method="get">
                <input hidden name="username" value="<?= $username ?>">
                <input hidden name="postID" value="<?= $item->id ?>">
                <button class="btn btn-block" type="submit" name="vote"
                    value="up-vote" <?= $disabled ?>>
                    <i
                        class="far fa-arrow-alt-circle-up <?= $successColorPost ?>"></i>
                </button>
            </form>
        </td>
        <td>
            <form action=<?= $urlToVotePost ?> method="get">
                <input hidden name="username" value="<?= $username ?>">
                <input hidden name="postID" value="<?= $item->id ?>">
                <button class="btn btn-block" type="submit" name="vote"
                    value="down-vote" <?= $disabled ?>>
                    <i
                        class="far fa-arrow-alt-circle-down <?= $dangerColorPost ?>"></i>
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
<?php
endif;
?>

<p>
    <a class="" href="<?= $urlToCreate ?>">Reply</a>
</p>