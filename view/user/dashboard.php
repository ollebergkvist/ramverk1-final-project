<?php

namespace Anax\View;

?>

<h1>Dashboard</h1>

<p>Username: <?= htmlentities($username) ?></p>
<p>Email: <?= htmlentities($email) ?></p>
<p>Score: <?= htmlentities($score) ?></p>
<p>Level: <?= htmlentities($level) ?></p>
<p>Created: <?= htmlentities($created) ?></p>

<?php if ($gravatarUrl) : ?>
    <img src="<?= htmlentities($gravatarUrl) ?>">
<?php endif; ?>
