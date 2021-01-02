<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "id" => "rm-menu",
    "wrapper" => null,
    "class" => "rm-default rm-mobile",

    // Here comes the menu items
    "items" =>  [
        "text" => "Register",
        "url" => "user/create",
        "title" => "Register.",
    ],
    [
        "text" => "Login",
        "url" => "user/login",
        "title" => "Login.",
    ],
    [
        "text" => "Forum",
        "url" => "forum",
        "title" => "Forum.",
    ],
    [
        "text" => "Tag",
        "url" => "tag",
        "title" => "Tag.",
    ],
];
