<?php
/**
 * Supply the basis for the navbar as an array.
 */

if (isset($_SESSION['permission']) && $_SESSION['permission'] === "user") {
    return [
        // Use for styling the menu
        "wrapper" => null,
        "class" => "my-navbar rm-default rm-desktop",

        // Here comes the menu items
        "items" => [
            [
                "text" => "<i class='fas fa-hat-wizard'></i> <span class='code'>Code</span>Wizards",
                "class" => "logotype",
                "url" => "home",
                "title" => "Home.",
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

if (isset($_SESSION['permission']) && $_SESSION['permission'] === 'admin') {
    return [
        // Use for styling the menu
        "wrapper" => null,
        "class" => "my-navbar rm-default rm-desktop",

        // Here comes the menu items
        "items" => [
            [
                "text" => "<i class='fas fa-hat-wizard'></i> <span class='code'>Code</span>Wizards",
                "class" => "logotype",
                "url" => "home",
                "title" => "Home.",
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
                "text" => "<i class='fas fa-user-ninja'></i> Admin",
                "url" => "admin",
                "title" => "Admin.",
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

return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",

    // Here comes the menu items
    "items" => [
        [
            "text" => "<i class='fas fa-hat-wizard'></i> <span class='code'>Code</span>Wizards",
            "class" => "logotype",
            "url" => "home",
            "title" => "Home.",
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