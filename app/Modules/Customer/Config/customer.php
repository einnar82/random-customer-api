<?php

return [
    'driver' => env('IMPORTER_DRIVER', 'json'),

    'json' => [
        'url' => 'https://randomuser.me/api/',
        'version' => '1.3',
        'count' => 100, // How many results to import
        'query' => [
            'nat' => 'au',
            'inc' =>  [
                'name', // Where first and last name
                'email',
                'login', // Where username
                'gender',
                'location', // Where country and city
                'phone'
            ],
            'results' => 100
        ]
    ]
];
