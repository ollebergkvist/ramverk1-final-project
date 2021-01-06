<?php

namespace Olbe19\Category;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Olbe19\Category\HTMLForm\CreateForm;
use Olbe19\Category\HTMLForm\DeleteForm;
use Olbe19\Category\HTMLForm\UpdateForm;
use Anax\DatabaseQueryBuilder\DatabaseQueryBuilder;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class CategoryController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Show all items.
     *
     * @return object as a response object
     */
    public function indexActionGet() : object
    {
        if (isset($_SESSION['permission'])) {
            $page = $this->di->get("page");
            $category = new Category();
            $category->setDb($this->di->get("dbqb"));
            
            // Count number of categories
            $sql = "SELECT COUNT(*) AS categories FROM Categories";
            $param = ["1"];
            $db = $this->di->get("dbqb");
            $db->connect();
            $res = $db->executeFetch($sql);
            $categories = $res->categories;
            $number = range(1, $categories);

            // Get number of topics in each category
            $sql = "SELECT COUNT(*) AS nroftopics FROM Topics WHERE category = ?";
            $db = $this->di->get("dbqb");
            $topicsArray = [];
            
            foreach ($number as $item) {
                $param = [];
                array_push($param, $item);
                $db->connect();
                $res = $db->executeFetch($sql, $param);
                $nrOfTopics = $res->nroftopics;
                array_push($topicsArray, $nrOfTopics);
            }

            // Insert number of topics into categories
            $sql = "UPDATE Categories SET topics = ? WHERE id = ?";
            $db = $this->di->get("dbqb");
            $index = 1;

            foreach ($topicsArray as $item) {
                $params = [];
                array_push($params, $item, $index);
                $db->connect();
                $res = $db->execute($sql, $params);
                $index++;
            }

            $page->add("category/view-all", [
                "items" => $category->findAll(),
            ]);

            return $page->render([
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
            $page = $this->di->get("page");
            $form = new CreateForm($this->di);
            $form->check();

            $page->add("category/crud/create", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
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
            $page = $this->di->get("page");
            $form = new DeleteForm($this->di);
            $form->check();

            $page->add("category/crud/delete", [
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
        if ($_SESSION['permission'] === "admin") {
            $page = $this->di->get("page");
            $form = new UpdateForm($this->di, $id);
            $form->check();

            $page->add("category/crud/update", [
                "form" => $form->getHTML(),
            ]);

            return $page->render([
                "title" => "Update an item",
            ]);
        }
        $this->di->get("response")->redirect("user/login");
    }
}