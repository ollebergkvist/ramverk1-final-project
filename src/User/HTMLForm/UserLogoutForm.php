<?php

namespace Olbe19\User\HTMLForm;

use Olbe19\User\User;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\Session\Session;

/**
 * Example of FormModel implementation.
 */
class UserLogoutForm extends FormModel
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
                "legend" => "User Logout"
            ],
            [
                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Logout",
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
        // Get session service
        $session = $this->di->get("session");

        // Delete session
        $session->destroy();;

        $this->di->get("response")->redirect("user/login");
    }
}
