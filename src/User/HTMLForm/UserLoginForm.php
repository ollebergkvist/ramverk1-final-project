<?php

namespace Olbe19\User\HTMLForm;

use Olbe19\User\User;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\Session\Session;

/**
 * Example of FormModel implementation.
 */
class UserLoginForm extends FormModel
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
                // "legend" => "User Login"
            ],
            [
                "username" => [
                    "type"        => "text",
                    // "description" => "Here you can place a description.",
                    "class" => "form-control",
                    "placeholder" => "Enter username",
                ],

                "password" => [
                    "type"        => "password",
                    //"description" => "Here you can place a description.",
                    "class" => "form-control",
                    "placeholder" => "Minimum 8 characters",
                ],

                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Log in",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
    }

    /**$
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from submitted form
        $username = $this->form->value("username");
        $password = $this->form->value("password");

        // Try to login (Active Record way)
        $user = new User();
        $user->setDb($this->di->get("dbqb"));

        $result = $user->verifyPassword($username, $password);

        if (!$result) {
            $this->form->rememberValues();
            $this->form->addOutput("User or password did not match.");
            return false;
        }

        $session = $this->di->get("session");
         // Store user details in variables
            $id = $user->id;
            $username = $user->username;
            $email = $user->email;
            $password = $user->password;
            $score = $user->score;
            $level = $user->level;
            $created = $user->created;
            $permission = $user->permission;
            $username = $user->username;
            $permission = $user->permission;
        
        if ($user->permission == "admin") {
            $session->set("username", $username);
            $session->set("permission", $permission);
            $this->di->get("response")->redirect("admin");
        }
        
        if ($user->permission == "user") {
            // Store user details in session
            $session->set("id", $id);
            $session->set("username", $username);
            $session->set("email", $email);
            $session->set("password", $password);
            $session->set("score", $score);
            $session->set("level", $level);
            $session->set("created", $created);
            $session->set("permission", $permission);

            $this->di->get("response")->redirect("home");
        }
    }
}