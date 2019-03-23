<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Post Types
    |--------------------------------------------------------------------------
    |
    | This value registers all the custom post types with wordpress.
    | You can generate new post type with php artisan make:posttype :name
    |
    | Ex: [App\Book::class, App\Portfolio::class]
    |
    */

    'post_types' => [],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | Register all the templates you might need in wordpress.
    |
    | Ex: ['post-type' => ['template-1', 'template-2']]
    |
    */

    'templates' => [
        'page' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | i18n
    |--------------------------------------------------------------------------
    |
    | If the app supports wpml, you can define it here.
    |
    */

    'i18n'              => true,
    'default_locale'    => 'en',
    'supported_locales' => ['en'], // set this to null if you want this to be queried from the database everytime.

    /*
    |--------------------------------------------------------------------------
    | Thumbnail sizes
    |--------------------------------------------------------------------------
    |
    | Register all the sizes wordpress should generate automatically
    | when uploading files.
    |
    */

    'image_sizes' => [
        'placeholder'  => [30, 30, false],
        'thumbnail'    => [150, 150],
        'medium'       => [300, 300, false],
        'medium_large' => [768, 768, false],
        'large'        => [1200, 1200, false],
        'larger'       => [1920, 1920, false],
    ],

    /*
    |--------------------------------------------------------------------------
    | Taxonomies
    |--------------------------------------------------------------------------
    |
    | Here you can register the taxonomies you wish and assign them to
    | post types.
    |
    | ex: [
    |       'Taxonomy Name' => [
    |         'post_types' => ['post', 'page'],
    |         'singular' => 'Singular name', // optional
    |         'plural' => 'Plural name', // optional
    |       ]
    |     ]
    |
    |
    */

    'taxonomies' => [],

    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    | Register all the menus you might need in wordpress.
    |
    */

    'menus' => [],
];
