<?php

/**
 * Config Error notifier
 */
return [
    'sendable' => true,
    'project' => 'Soloban',
    'masked_parameters' => [
        'password'
    ],
    'driver' => 'email',
    'driver_config' => [
        'transport' => 'notifier',
        'to' => ['duytt@nal.vn', 'vunt@nal.vn']
    ]
];

