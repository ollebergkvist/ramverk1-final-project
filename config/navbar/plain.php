<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "class" => "my-navbar",

    // Here comes the menu items/structure
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
