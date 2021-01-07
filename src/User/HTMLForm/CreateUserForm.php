<?php

namespace Olbe19\User\HTMLForm;

use Olbe19\User\User;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm extends FormModel
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
                "legend" => "Register Account",
            ],
            [
                "username" => [
                    "type"        => "text",
                    "class"        => "form-control",
                    // "description" => "Here you can place a description.",
                    // "placeholder" => "you@domain.com",
                ],

                "email" => [
                    "type"        => "text",
                    "class"        => "form-control",
                    // "description" => "Here you can place a description.",
                    // "placeholder" => "you@domain.com",
                ],

                "password" => [
                    "type"        => "password",
                    "class"        => "form-control",
                    // "description" => "Here you can place a description.",
                    "placeholder" => "Must contain at least 6 characters",
                ],

                "password-again" => [
                    "type"        => "password",
                    "class"        => "form-control",
                    "validation" => [
                        "match" => "password"
                    ],
                ],

                "submit" => [
                    "type" => "submit",
                    "class"        => "btn btn-primary btn-block",
                    "value" => "Register",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from submitted form
        $username = $this->form->value("username");
        $email = $this->form->value("email");
        $password = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");
        $timestamp = date("Y-m-d h:i:s");

        // Check if password matches
        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        // Save to database (Active Record way)
        $user = new User(); // Create user
        $user->setDb($this->di->get("dbqb"));// Create connection between object and db
        $user->created = $timestamp; // Save timestamp
        $user->score = 0; // Save timestamp
        $user->level = "NOOB"; // Save timestamp
        $user->permission = "user"; // Save timestamp
        $user->username = $username; // Save username
        $user->email = $email; // Save email
        $user->setPassword($password); // Save password
        $user->save(); // Save to db

        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("user/login");
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