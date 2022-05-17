<?php 

return [
    'expose_api' => false,
    'force_class' => false,

    'styles' => [],
    'scripts' => [],

    'canvas' => [
        'styles' => [],
        'scripts' => [],
    ],

    'assets' => [
        'disk' => 'public', //Default: local
        'path' => null, //Default: 'laravel-grapesjs/media',
        'upload_url' => null,
        'upload_url' => null,
    ],

    'storage_manager' => [
        'autosave' => true,
        'steps_before_save' => 10,
    ],

    'plugins' => [
        'default' => [
            'basic_blocks' => true,
            'bootstrap4_blocks' => false,
            'image_editor' => false,
            'custom_fonts' => [],
        ],
        'custom' => [],
    ],
];