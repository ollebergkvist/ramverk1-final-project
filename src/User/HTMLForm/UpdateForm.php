<?php

namespace Olbe19\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Olbe19\User\User;

/**
 * Form to update an item.
 */
class UpdateForm extends FormModel
{
    /**
     * Constructor injects with DI container and the id to update.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     * @param integer             $id to update
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);
        $user = $this->getItemDetails();
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update user details",
            ],
            [
                "username" => [
                    "type" => "text",
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                    "readonly" => true,
                    "value" => $user->username,
                ],

                "email" => [
                    "type" => "text",
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                    "value" => $user->email,
                ],

                "password" => [
                    "type" => "password",
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                    "value" => $user->password,
                ],

                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Save",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "reset" => [
                    "type"      => "reset",
                    "class" => "btn btn-primary btn-block mt-1",
                ],
            ]
        );
    }

    /**
     * Get details on item to load form with.
     *
     * @param integer $id get details on item with id.
     *
     * @return User
     */
    public function getItemDetails() : object
    {   
        $session = $this->di->get("session");
        $username = $session->get("username");
        
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("username", $username);
        return $user;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {   
        $session = $this->di->get("session");
        $username = $session->get("username");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("username", $username);
        $user->email = $this->form->value("email");
        $user->setPassword($this->form->value("password"));
        $user->save2();
        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("user")->send();
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