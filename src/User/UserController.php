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
use Olbe19\Topic\Topic;

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
        // General framework setup
        $page = $this->di->get("page");
        $title = "Dashboard";
        $session = $this->di->get("session");
        $gravatar = new Gravatar();

        // Get user data from session
        $id = $session->get("id");
        $username = $session->get("username");
        $email = $session->get("email");
        $password = $session->get("password");
        $score = $session->get("score");
        $level = $session->get("level");
        $created = $session->get("created");

        // Validate and get gravatar
        if ($gravatar->validate_gravatar($email)) {
            $gravatarUrl = $gravatar->gravatar_image($email, 200);
        }

        // Data to send to view
        $data = [
            "id" => $id ?? null,
            "username" => $username ?? null,
            "email" => $email ?? null,
            "password" => $password ?? null,
            "score" => $score ?? null,
            "level" => $level ?? null,
            "created" => $created ?? null,
            "gravatarUrl" => $gravatarUrl ?? null
        ];

        $page->add("user/dashboard", $data);

        return $page->render([
            "title" => $title,
        ]);
    }

    public function viewActionGet() : object
    {
        // General framework setup
        $page = $this->di->get("page");
        $title = "Dashboard";
        $session = $this->di->get("session");
        $gravatar = new Gravatar();

        // Get user data from session
        $id = $session->get("id");
        $username = $session->get("username");
        $email = $session->get("email");
        $password = $session->get("password");
        $score = $session->get("score");
        $level = $session->get("level");
        $created = $session->get("created");

        // Validate and get gravatar
        if ($gravatar->validate_gravatar($email)) {
            $gravatarUrl = $gravatar->gravatar_image($email, 200);
        }

        // Data to send to view
        $data = [
            "id" => $id ?? null,
            "username" => $username ?? null,
            "email" => $email ?? null,
            "password" => $password ?? null,
            "score" => $score ?? null,
            "level" => $level ?? null,
            "created" => $created ?? null,
            "gravatarUrl" => $gravatarUrl ?? null
        ];

        $page->add("user/dashboard", $data);

        return $page->render([
            "title" => $title,
        ]);
    }

    public function showActionGet($author) : object
    {   
        // General framework setup
        $page = $this->di->get("page");
        $title = "Dashboard";
        $session = $this->di->get("session");

        // Get all posts by author
        $posts = $this->topic->getPostsByAuthor($author);


        // Data to send to view
        $data = [
            "items" => $posts,
            "author" => $author,
        ];

        $page->add("user/view-all", $data);

        return $page->render([
            "title" => $title,
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
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new UserLoginForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
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
        $page = $this->di->get("page");
        $form = new UserLogoutForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
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
        $page = $this->di->get("page");
        $form = new CreateUserForm($this->di);
        $form->check();

        $page->add("anax/v2/article/default", [
            "content" => $form->getHTML(),
        ]);

        return $page->render([
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
        if (isset($_SESSION['username'])) {
            $page = $this->di->get("page");
            $form = new DeleteForm($this->di);
            $form->check();

            $page->add("user/crud/delete", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Delete an item",
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
    public function updateAction(int $id) : object
    {
        if (isset($_SESSION['username'])) {
            $page = $this->di->get("page");
            $form = new UpdateForm($this->di, $id);
            $form->check();

            $page->add("user/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Update an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}