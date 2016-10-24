<?php
return [
    'simplefm' => [
        'connection' => [
            'uri' => 'https://example.com',
            'database' => 'sample-database',

            // The following settings are optional and can be ommited. These are keys pointing to objects in your
            // dependency container. This package provides ready-made factories which you can use.
            'http_client' => 'soliant.simplefm.expressive.http-client',
            'identity_handler' => 'soliant.simplefm.expressive.identity-handler',
            'logger' => 'soliant.simplefm.expressive.logger',
        ],

        'result_set_client' => [
            // Set this to the time zone of your file maker server
            'time_zone' => 'America/Los_Angeles',
        ],

        'authenticator' => [
            'identity_handler' => 'soliant.simplefm.expressive.identity-handler',
            'identity_layout' => 'identity-layout',
            'username_field' => 'username-field',
        ],

        'repository_builder' => [
            'xml_folder' => '/path/to/xml/folder',

            // The following settings are optional.
            'proxy_folder' => '/path/to/proxy/folder',
            'additional_types' => [
                'type-name-in-xml' => 'container-key',
            ],
        ],

        // This config is only required when you use "soliant.simplefm.expressive.identity-handler"
        'identity_handler' => [
            'key' => 'some-strong-encryption-key',
        ],

        // This config is only required when you use "soliant.simplefm.expressive.logger"
        'logger' => [
            'path' => '/path/to/simplefm/log',
        ],
    ],
];
