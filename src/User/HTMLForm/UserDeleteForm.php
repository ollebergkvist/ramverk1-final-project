<?php

namespace Olbe19\User\HTMLForm;

use Olbe19\User\User;
use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Anax\Session\Session;

/**
 * Example of FormModel implementation.
 */
class UserDeleteForm extends FormModel
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
                "legend" => "Delete account"
            ],
        
            [   
                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Delete account",
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
        $session = $this->di->get("session");
        $username = $session->get("username");
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("username", $username);
        $user->delete($username, "username");
        $session->destroy();
        return true;
    }

    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("user/login")->send();
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