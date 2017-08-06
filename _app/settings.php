<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // lembrar de setar falso para produção
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../_pages/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'adpata-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
];
