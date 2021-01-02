<?php

namespace Olbe19\Topic\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Olbe19\Topic\Topic;
use Olbe19\Post\Post;

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
                "legend" => "Create new topic",
            ],
            [
                "category" => [
                    "type" => "select",
                    "class" => "form-control",
                    "options" => ["", "General Discussion", "Get Help", "Show & Tell"],
                    "validation" => ["not_empty"],
                ],

                "subject" => [
                    "type" => "text",
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                ],

                "content" => [
                    "type" => "textarea",
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                ],

                "tag" => [
                    "type" => "text",
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                ],

                "submit" => [
                    "type" => "submit",
                    "class" => "btn btn-primary btn-block",
                    "value" => "Post topic",
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

        // Topic query
        $topic = new Topic();
        $topic->setDb($this->di->get("dbqb"));

        $topic->subject  = $this->form->value("subject");
        $topic->date = $timestamp;
        $topic->category = $this->form->value("category");
        $topic->author = $session->get("username");
        $topic->tags = $this->form->value("tag");

        $topic->save();
        $topicID = $topic->id;

        // Post query
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        $post->content = $this->form->value("content");
        $post->date = $timestamp;
        $post->topic = $topicID;
        $post->author = $session->get("username");

        $post->save();

        return true;
    }



    /**
     * Callback what to do if the form was successfully submitted, this
     * happen when the submit callback method returns true. This method
     * can/should be implemented by the subclass for a different behaviour.
     */
    public function callbackSuccess()
    {
        $this->di->get("response")->redirect("topic")->send();
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