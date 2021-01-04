<?php

namespace Anax\View;

$urlToDeleteAccount = "hej";
$urlToEditAccount = "tjo";

?>



<h1>Dashboard</h1>

<p>
    <a class="btn btn-secondary" href="<?= $urlToEditAccount ?>"><i
            class="far fa-edit"></i> Edit account</a>
    <a class="btn btn-secondary" href="<?= $urlToDeleteAccount?>"><i
            class="far fa-trash-alt"></i> Delete
        account</a>
    <!-- <a href="<?= $urlToDelete ?>">Delete</a> -->
</p>

<p>Username: <?= htmlentities($username) ?></p>
<p>Email: <?= htmlentities($email) ?></p>
<p>Score: <?= htmlentities($score) ?></p>
<p>Level: <?= htmlentities($level) ?></p>
<p>Created: <?= htmlentities($created) ?></p>

<?php if ($gravatarUrl) : ?>
<img class="gravatar" src="<?= htmlentities($gravatarUrl) ?>">
<?php endif; ?>