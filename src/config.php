<?php 

return [
    'expose_api' => false,
    'force_class' => false,

    'styles' => [
        'vendor/laravel-grapesjs/assets/editor.css'
    ],
    'scripts' => [
        'vendor/laravel-grapesjs/assets/editor.js'
    ],

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
            'code_editor' => true,
            'image_editor' => false,
            'custom_fonts' => [],
            'templates' => true,
        ],
        'custom' => [
            [
                'name' => 'grapesjs-plugin-forms',
                'options' => [],
                'scripts' => [
                    'https://unpkg.com/grapesjs-plugin-forms@2.0.1/dist/grapesjs-plugin-forms.min.js',
                ]
            ],
        ],
    ],
];