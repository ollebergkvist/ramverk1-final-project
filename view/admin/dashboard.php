<?php

namespace Anax\View;

$urlToAddCategories = url("category/create");
$urlToEditCategories = url("category/edit");
$urlToEditCategories = url("category/delete");
$urlToEditTopics = url("topic/edit");
$urlToDeleteTopics = url("topic/delete");
$urlToEditPosts = url("post/edit");
$urlToDeletePosts = url("post/delete");
$urlToDeleteUsers = url("user/delete");
?>


<p>
<p>
    <a class="btn btn-secondary" href="<?= $urlToAddCategories ?>"><i
            class="far fa-edit"></i> Add category</a>
    <a class="btn btn-secondary" href="<?= $urlToEditCategories ?>"><i
            class="far fa-edit"></i> Edit category</a>
    <a class="btn btn-secondary" href="<?= $urlToEditCategories ?>"><i
            class="far fa-trash-alt"></i> Delete category</a>
</p>
<p>
    <a class="btn btn-secondary" href="<?= $urlToEditTopics ?>"><i
            class="far fa-edit"></i> Edit topics</a>
    <a class="btn btn-secondary" href="<?= $urlToDeleteTopics ?>"><i
            class="far fa-trash-alt"></i> Delete topics</a>
</p>
<p><a class="btn btn-secondary" href="<?= $urlToEditPosts ?>"><i
            class="far fa-edit"></i> Edit posts</a>

    <a class="btn btn-secondary" href="<?= $urlToDeletePosts ?>"><i
            class="far fa-trash-alt"></i> Delete posts</a>
</p>

<p><a class="btn btn-secondary" href="<?= $urlToDeleteUsers?>"><i
            class="far fa-trash-alt"></i> Delete users</a></p>
</p>