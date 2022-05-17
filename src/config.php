<?php 

return [
    'expose_api' => false,
    'image_editor' => false,
    'force_class' => false,

    'styles' => [],
    'scripts' => [],
    'puglings' => [],
    'fonts' => [],

    'assets' => [
        'disk' => 'public', //Default: local
        'path' => null, //Default: 'laravel-grapesjs/media',
        'upload_url' => null,
        'upload_url' => null,
    ],

    'canvas' => [
        'styles' => [],
        'scripts' => [],
    ],

    'storage_manager' => [
        'autosave' => true,
        'steps_before_save' => 10,
    ],
];