<?php
/**
 * Configuration file to create User IP as $di service.
 */

return [
    "services" => [
        "topicmodel" => [
            "active" => false,
            "shared" => true,
            "callback" => function () {
                $topicModel = new \Olbe19\Topic\TopicModel();
                return $topicModel;
            },
        ],
    ],
];