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
use Olbe19\Post\Post;
use Olbe19\Vote\Vote;
use Olbe19\Vote2Topic\Vote2Topic;

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
        $this->user = new User();
        $this->user->setDb($this->di->get("dbqb"));
        $this->post = new Post();
        $this->post->setDb($this->di->get("dbqb"));
        $this->topic = new Topic();
        $this->topic->setDb($this->di->get("dbqb"));
        $this->tag2topic = new Tag2Topic();
        $this->tag2topic->setDb($this->di->get("dbqb"));
        $this->gravatar = new Gravatar();
        $this->page = $this->di->get("page");
        $this->session = $this->di->get("session");
        $this->vote = new Vote();
        $this->vote->setDb($this->di->get("dbqb"));
        $this->vote2topic = new Vote2Topic();
        $this->vote2topic->setDb($this->di->get("dbqb"));
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
            $username = $this->session->get("username");

            // Get user from db
            $user = $this->user->getUserDetails($username);

            // Validate and get gravatar
            if ($this->gravatar->validate_gravatar($email)) {
                $gravatarUrl = $this->gravatar->gravatar_image($email, 200);
            }

            // Data to send to view
            $data = [
                "id" => $user->id ?? null,
                "username" => $user->username ?? null,
                "email" => $user->email ?? null,
                "password" => $user->password ?? null,
                "score" =>  $user->score ?? null,
                "level" => $user->level ?? null,
                "created" => $user->created ?? null,
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
            $title = "User activity";

            // Get all topics by author
            $topics = $this->topic->getTopicsByAuthor($author);

            // Get all posts by author
            $posts = $this->post->getPostsByAuthor($author);

            // Data to send to view
            $result = [];

            $userScore = $this->user->getUserDetails($author)->score;
            $postsScore = $this->post->getPoints($author)[0]->sum;
            $topicsScore = $this->topic->getPoints($author)[0]->sum;
            $totalScore = $userScore + $postsScore + $topicsScore;

            $topicsUserVotes = $this->vote2topic->getUserVotes($author)->count;
            $postsUserVotes = $this->vote->getUserVotes($author)->count;
            $totalVotes = $topicsUserVotes + $postsUserVotes;

            $level = "";
            
            if ($totalScore == 0 ) {
                $level = "beginner";
            } elseif ($totalScore >= 1 && $totalScore <= 3 ) {
                $level = "student";
            } elseif ($totalScore > 3 && $totalScore <= 5 ) {
                $level = "teacher";
            } elseif ($totalScore > 5 && $totalScore <= 10 ) {
                $level = "pro";
            } elseif ($totalScore > 10) {
                $level = "master";
            }

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
                "items2" => $posts,
                "score" => $totalScore,
                "level" => $level,
                "votes" => $totalVotes
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

        $this->page->add("user/crud/logout", [
            "form" => $form->getHTML(),
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