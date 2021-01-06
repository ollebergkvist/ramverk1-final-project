<?php

namespace Olbe19\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Olbe19\Post\Post;

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
                "legend" => "Delete a post",
            ],
            [
                "select" => [
                    "type"        => "select",
                    "class"        => "form-control",
                    "label"       => "Select post to delete:",
                    "options"     => $this->getAllItems(),
                ],

                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Delete post",
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
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        $posts = ["-1" => "Select a post..."];
        foreach ($post->findAll() as $obj) {
            $posts[$obj->id] = "{$obj->content} ({$obj->id})";
        }

        return $posts;
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->find("id", $this->form->value("select"));
        $post->delete();
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



    /**
     * Callback what to do if the form was unsuccessfully submitted, this
     * happen when the submit callback method returns false or if validation
     * fails. This method can/should be implemented by the subclass for a
     * different behaviour.
     */
    public function callbackFail()
    {
        $this->di->get("response")->redirectSelf()->send();
    }
}