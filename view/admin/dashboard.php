<?php

namespace Anax\View;

$urlToAddCategories = url("forum/create");
$urlToEditCategories = url("forum/update");
$urlToDeleteCategories = url("forum/delete");
$urlToEditTopics = url("topic/update");
$urlToDeleteTopics = url("topic/delete");
$urlToEditPosts = url("post/update");
$urlToAddTags = url("tag/create");
$urlToEditTags = url("tag/update");
$urlToDeleteTags = url("tag/delete");
$urlToDeletePosts = url("post/delete");
$urlToDeleteUsers = url("user/delete");
?>

<p>
<p>
    <a class="btn btn-secondary" href="<?= $urlToAddCategories ?>"><i
            class="far fa-edit"></i> Add category</a>
    <a class="btn btn-secondary" href="<?= $urlToEditCategories ?>"><i
            class="far fa-edit"></i> Edit category</a>
    <a class="btn btn-secondary" href="<?= $urlToDeleteCategories ?>"><i
            class="far fa-trash-alt"></i> Delete category</a>
</p>
<p>
    <a class="btn btn-secondary" href="<?= $urlToEditTopics ?>"><i
            class="far fa-edit"></i> Edit topic</a>
    <a class="btn btn-secondary" href="<?= $urlToDeleteTopics ?>"><i
            class="far fa-trash-alt"></i> Delete topic</a>
</p>
<p>
    <a class="btn btn-secondary" href="<?= $urlToEditPosts ?>"><i
            class="far fa-edit"></i> Edit post</a>
    <a class="btn btn-secondary" href="<?= $urlToDeletePosts ?>"><i
            class="far fa-trash-alt"></i> Delete post</a>
</p>
<p>
    <a class="btn btn-secondary" href="<?= $urlToAddTags ?>"><i
            class="far fa-edit"></i> Add tag</a>
    <a class="btn btn-secondary" href="<?= $urlToEditTags ?>"><i
            class="far fa-edit"></i> Edit tag</a>
    <a class="btn btn-secondary" href="<?= $urlToDeleteTags ?>"><i
            class="far fa-trash-alt"></i> Delete tag</a>
</p>
<p><a class="btn btn-secondary" href="<?= $urlToDeleteUsers?>"><i
            class="far fa-trash-alt"></i> Delete user</a></p>
</p>