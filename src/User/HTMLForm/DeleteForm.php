<?php

namespace Olbe19\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Olbe19\User\User;

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
                "username" => __CLASS__,
                "legend" => "Delete a user",
            ],
            [
                "select" => [
                    "type"        => "select",
                    "class"        => "form-control",
                    "label"       => "Select user to delete:",
                    "options"     => $this->getAllItems(),
                ],

                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Delete user",
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
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $users = ["-1" => "Select a user..."];
        foreach ($user->findAll() as $obj) {
            $users[$obj->username] = "{$obj->username}";
        }

        return $users;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $tag = new User();
        $tag->setDb($this->di->get("dbqb"));
        $tag->find("username", $this->form->value("select"));
        $tag->delete($this->form->value("select"), "username");
        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirectSelf()->send();
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