<?php

namespace Olbe19\Topic\HTMLForm;

use Anax\HTMLForm\FormModel;
use Psr\Container\ContainerInterface;
use Olbe19\Topic\Topic;

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
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);
        $topic = $this->getItemDetails($id);
        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "Update details of topic",
            ],
            [
                "id" => [
                    "type" => "hidden",
                    "validation" => ["not_empty"],
                    "class" => "form-control",
                    "readonly" => true,
                    "value" => $topic->id,
                ],

                "subject" => [
                    "type" => "text",
                    "class" => "form-control",
                    "validation" => ["not_empty"],
                    "value" => $topic->subject,
                ],

                "author" => [
                    "type" => "text",
                    "class" => "form-control",
                    "readonly" => true,
                    "validation" => ["not_empty"],
                    "value" => $topic->author,
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
     * @return Topic
     */
    public function getItemDetails($id) : object
    {
        $topic = new Topic();
        $topic->setDb($this->di->get("dbqb"));
        $topic->find("id", $id);
        return $topic;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return bool true if okey, false if something went wrong.
     */
    public function callbackSubmit() : bool
    {
        $topic = new Topic();
        $topic->setDb($this->di->get("dbqb"));
        $topic->find("id", $this->form->value("id"));
        $topic->subject = $this->form->value("subject");
        $topic->author = $this->form->value("author");
        $topic->save();
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