<?php
return [
    'simplefm' => [
        'connection' => [
            'base_uri' => 'https://example.com',
            'username' => 'filemaker-username',
            'password' => 'filemaker-password',
            'database' => 'sample-database',

            // Set this to the time zone of your file maker server
            'time_zone' => 'America/Los_Angeles',
        ],

        'client' => [
            // The following setting is required and you must set an appropriate HTTP client in your container.
            'http_client' => 'http-client.container.key',
        ],

        'repository_builder' => [
            'xml_folder' => '/path/to/xml/folder',

            // The following settings are optional.
            'proxy_folder' => '/path/to/proxy/folder',
            'additional_types' => [
                'type-name-in-xml' => 'container-key',
            ],
        ],
    ],
];
