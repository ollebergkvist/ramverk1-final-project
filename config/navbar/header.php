<?php
/**
 * Supply the basis for the navbar as an array.
 */

if (isset($_SESSION['username'])) {
    return [
        // Use for styling the menu
        "wrapper" => null,
        "class" => "my-navbar rm-default rm-desktop",

        // Here comes the menu items
        "items" => [
            [
                "text" => "<i class='fas fa-hat-wizard'></i> CodeWizards",
                "class" => "logotype",
                "url" => "forum",
                "title" => "Forum.",
            ],
            [
                "text" => "<i class='fas fa-comments'></i> Forum",
                "url" => "forum",
                "title" => "Forum.",
            ],
            [
                "text" => "<i class='fas fa-tags'></i> Tags",
                "url" => "tag",
                "title" => "Tags.",
            ],
            [
                "text" => "<i class='fas fa-user-secret'></i> Profile",
                "url" => "user",
                "title" => "Account.",
            ],
            [
                "text" => "<i class='fas fa-info-circle'></i> About",
                "url" => "about",
                "title" => "About.",
            ],
            [
                "text" => "<i class='fas fa-sign-out-alt'></i> Sign out",
                "url" => "user/logout",
                "title" => "Logout.",
            ],
        ],
    ];
}

if (isset($_SESSION['admin'])) {
    return [
        // Use for styling the menu
        "wrapper" => null,
        "class" => "my-navbar rm-default rm-desktop",

        // Here comes the menu items
        "items" => [
            [
                "text" => "<i class='fas fa-hat-wizard'></i> CodeWizards",
                "class" => "logotype",
                "url" => "forum",
                "title" => "Forum.",
            ],
            [
                "text" => "Forum",
                "url" => "forum",
                "title" => "Forum.",
            ],
            [
                "text" => "Admin",
                "url" => "admin",
                "title" => "Admin.",
            ],
            [
                "text" => "Account",
                "url" => "user",
                "title" => "Account.",
            ],
            [
                "text" => "Topic",
                "url" => "topic",
                "title" => "Topic.",
            ],
            [
                "text" => "Post",
                "url" => "post",
                "title" => "Post.",
            ],
            [
                "text" => "<i class='fas fa-tags'></i> Tags",
                "url" => "tag",
                "title" => "Tags.",
            ],
            [
                "text" => "Logout",
                "url" => "user/logout",
                "title" => "Logout.",
            ],
        ],
    ];
}

return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",

    // Here comes the menu items
    "items" => [
        [
            "text" => "<i class='fas fa-hat-wizard'></i> CodeWizards",
            "class" => "logotype",
            "url" => "forum",
            "title" => "Forum.",
        ],
        [
            "text" => "Register",
            "url" => "user/create",
            "title" => "Register.",
        ],
        [
            "text" => "Login",
            "url" => "user/login",
            "title" => "Login.",
        ],
    ],
];