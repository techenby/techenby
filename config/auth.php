<?php

return [

    'providers' => [
        'users' => [
            'driver' => 'statamic',
        ],
    ],

    'passwords' => [
        'resets' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'activations' => [
            'provider' => 'users',
            'table' => 'password_activations',
            'expire' => 4320,
            'throttle' => 60,
        ],
    ],

];
