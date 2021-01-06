<?php

namespace Olbe19\Category\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Olbe19\Category\Category;

/**
 * Form to delete an item.
 */
class DeleteForm extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Delete category",
            ],
            [
                "select" => [
                    "type"        => "select",
                    "class" =>  "form-control",
                    "label"       => "Select category to delete:",
                    "options"     => $this->getAllItems(),
                ],

                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Delete category",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Get all items as array suitable for display in select option dropdown.
     *
     * @return array with key value of all items.
     */
    protected function getAllItems() : array
    {
        $category = new Category();
        $category->setDb($this->di->get("dbqb"));

        $categories = ["-1" => "Select a category.."];
        foreach ($category->findAll() as $obj) {
            $categories[$obj->id] = "{$obj->name} ({$obj->id})";
        }

        return $categories;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $category = new Category();
        $category->setDb($this->di->get("dbqb"));
        $category->find("id", $this->form->value("select"));
        $category->delete();
        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("admin")->send();
    }



    // /**
    //  * Callback what to do if the form was unsuccessfully submitted, this
    //  * happen when the submit callback method returns false or if validation
    //  * fails. This method can/should be implemented by the subclass for a
    //  * different behaviour.
    //  */
    // public function callbackFail()
    // {
    //     $this->di->get("response")->redirectSelf()->send();
    // }
}