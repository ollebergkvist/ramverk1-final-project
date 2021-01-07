<?php

namespace Olbe19\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Gravatar\Gravatar;
use Olbe19\User\HTMLForm\UserLoginForm;
use Olbe19\User\HTMLForm\UserLogoutForm;
use Olbe19\User\HTMLForm\CreateUserForm;
use Olbe19\User\HTMLForm\UpdateForm;
use Olbe19\User\HTMLForm\DeleteForm;
use Olbe19\User\HTMLForm\UserDeleteForm;
use Olbe19\Topic\Topic;
use Olbe19\Tag2Topic\Tag2Topic;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convenient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->topic = new Topic();
        $this->topic->setDb($this->di->get("dbqb"));
        $this->tag2topic = new Tag2Topic();
        $this->tag2topic->setDb($this->di->get("dbqb"));
        $this->gravatar = new Gravatar();
        $this->page = $this->di->get("page");
        $this->session = $this->di->get("session");
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {   
        if(isset($_SESSION["permission"])) {
            // General framework setup
            $title = "User dashboard";
            $email = $this->session->get("email");

            // Validate and get gravatar
            if ($this->gravatar->validate_gravatar($email)) {
                $gravatarUrl = $this->gravatar->gravatar_image($email, 200);
            }

            // Data to send to view
            $data = [
                "id" => $this->session->get("id") ?? null,
                "username" => $this->session->get("username") ?? null,
                "email" => $email ?? null,
                "password" => $this->session->get("password") ?? null,
                "score" =>  $this->session->get("score") ?? null,
                "level" => $this->session->get("level") ?? null,
                "created" => $this->session->get("created") ?? null,
                "gravatarUrl" => $gravatarUrl ?? null
            ];

            $this->page->add("user/dashboard", $data);

            return $this->page->render([
                "title" => $title,
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    public function showActionGet($author) : object
    {   
        if(isset($_SESSION["permission"])) {
            // General framework setup
            $title = "User topics";

            // Get all posts by author
            $topics = $this->topic->getTopicsByAuthor($author);

            // Data to send to view
            $result = [];

            foreach ($topics as $key => $value) {
                // SQL
                $where = "Tag2Topic.topic = ?";
                $value = $topics[$key]->id;
                $table = "Tags";
                $join = "Tags.id = Tag2Topic.tag";
                $select = "Tag2Topic.*, Tags.name";

                // Get tag names
                $tags = $this->tag2topic->findAllWhereJoin(
                    $where, 
                    $value, 
                    $table, 
                    $join, 
                    $select 
                );

                // Push combined data to $result array
                array_push($result,
                [
                    "author" => $author,
                    "topic" => $topics[$key],
                    "tags" => $tags,
                ]);
            }

            // Data to send to view
            $data = [
                "items" => $result,
            ];

            $this->page->add("user/view-all", $data);

            return $this->page->render([
                "title" => $title,
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $form = new UserLoginForm($this->di);
        $form->check();

        $this->page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $this->page->render([
            "title" => "Login",
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function logoutAction() : object
    {
        $form = new UserLogoutForm($this->di);
        $form->check();

        $this->page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $this->page->render([
            "title" => "Logout",
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
            $form = new CreateUserForm($this->di);
            $form->check();

            $this->page->add("anax/v2/article/default", [
                "content" => $form->getHTML(),
            ]);

            return $this->page->render([
                "title" => "Register account",
            ]);
    }

    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        if ($_SESSION['permission'] === "admin") {
            $form = new DeleteForm($this->di);
            $form->check();

            $this->page->add("user/crud/delete", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
                "title" => "Delete an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function removeAction() : object
    {
        if ($_SESSION['permission'] === "user") {
            $form = new UserDeleteForm($this->di);
            $form->check();

            $this->page->add("user/crud/remove", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
                "title" => "Delete account",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction() : object
    {
        if (isset($_SESSION['username'])) {
            $form = new UpdateForm($this->di);
            $form->check();

            $this->page->add("user/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
                "title" => "Update an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}