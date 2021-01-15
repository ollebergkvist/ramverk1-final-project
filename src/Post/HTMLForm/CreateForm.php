<?php

namespace Olbe19\Post\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Olbe19\Post\Post;
use Olbe19\User\User;

/**
 * Form to create an item.
 */
class CreateForm extends FormModel
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
                "legend" => "Details of the post",
            ],
            [
                "content" => [
                    "type" => "text",
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Create post",
                    "callback" => [$this, "callbackSubmit"]
                ],
            ]
        );
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
        $timestamp = date("Y-m-d h:i:s");
        $topic = $session->get("topicID");

        // Post query
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        $post->content = $this->form->value("content");
        $post->date = $timestamp;
        $post->topic = $topic; 
        $post->rank = 0; 
        $post->accepted = false; 
        $post->author = $session->get("username");

        $post->save();

        // User query
        $where = "username = ?";
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->findWhere($where, [$session->get("username")]);
        $user->score = $user->score + 1;
        $user->savePoints($session->get("username"));

        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("post")->send();
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