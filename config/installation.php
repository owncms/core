<?php

return [
    'phpVersion' => '8.1.0',
    'requirements' => [
        'php' => [
            'bcmath',
            'ctype',
            'hash',
            'session',
            'zip',
            'fileinfo',
            'JSON',
            'mbstring',
            'openssl',
            'PDO',
            'Tokenizer',
//            'SimpleXML',
//            'xml',
//            'xmlreader',
//            'xmlwriter',
        ],
        'apache' => [
            'mod_rewrite',
            'mod_negotiation'
        ],
        'permissions' => [
            'bootstrap/cache' => base_path('bootstrap/cache'),
            'storage/app' => base_path('storage/app'),
            'storage/framework' => base_path('storage/framework'),
            'storage/logs' => base_path('storage/logs'),
        ]
    ]
];
