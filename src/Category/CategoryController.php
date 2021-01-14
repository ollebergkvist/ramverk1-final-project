<?php

namespace Olbe19\Category;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Category\HTMLForm\CreateForm;
use Olbe19\Category\HTMLForm\DeleteForm;
use Olbe19\Category\HTMLForm\UpdateForm;
use Olbe19\Topic\Topic;
use Anax\DatabaseQueryBuilder\DatabaseQueryBuilder;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class CategoryController implements ContainerInjectableInterface
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
        $this->category = new Category();
        $this->category->setDb($this->di->get("dbqb"));
        $this->topic = new Topic();
        $this->topic->setDb($this->di->get("dbqb"));
        $this->session = $this->di->get("session");
        $this->page = $this->di->get("page");
    }

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        if (isset($_SESSION['permission'])) {

            // Get all categories from db
            $categories = $this->category->findAll();

            // Data to send to view
            $result = [];

            foreach ($categories as $key => $value) {
                // Get number of topics in each category
                $topics = $this->topic->getNumberOfTopicsInCategory($categories[$key]->id);

                // Push combined data to $result array
                array_push($result,
                [
                    "category" => $categories[$key],
                    "posts" => $topics[0]->count,
                ]);

            }

            $this->page->add("category/view-all", [
                "items" => $result,
            ]);

            return $this->page->render([
                "title" => "Categories",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to view topics connected to category.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function viewAction(int $id) 
    {
        if (isset($_SESSION['permission'])) {
            $session = $this->di->get("session");
            $session->set("categoryID", $id);
            $this->di->get("response")->redirect("topic");
        }
        // $this->di->get("response")->redirect("user/login");
    }

    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        if ($_SESSION['permission'] === "admin") {
            $form = new CreateForm($this->di);
            $form->check();

            $this->page->add("category/crud/create", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
                "title" => "Create a category",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
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

            $this->page->add("category/crud/delete", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
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
        if ($_SESSION['permission'] === "admin") {
            $form = new UpdateForm($this->di, $id);
            $form->check();

            $this->page->add("category/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $this->page->render([
                "title" => "Update an item",
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
    public function editAction() : object
    {
        if ($_SESSION['permission'] === "admin") {
            $category = new Category();
            $category->setDb($this->di->get("dbqb"));
            
            $this->page->add("category/crud/edit", [
                "items" => $category->findAll(),
            ]);

            return $this->page->render([
                "title" => "Categories",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}