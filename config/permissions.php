<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Entities
    |--------------------------------------------------------------------------
    |
    | This array defines all the entities that can have permissions in your application.
    |
    */
    //    'entities' => [
    //        'Club',
    //        'Role',
    //        'Player',
    //        'Matchday',
    //        'FeeType',
    //        'CompetitionType',
    //        'Transaction'
    //    ],

    /*
    |--------------------------------------------------------------------------
    | Available Actions
    |--------------------------------------------------------------------------
    |
    | This array defines all the possible actions that can be performed on entities.
    |
    */
    //    'actions' => [
    //        'list',
    //        'view',
    //        'create',
    //        'update',
    //        'delete',
    //    ],
    'Club' => [
        'view',
        'update',
        'delete',
    ],
    'Role' => [
        'list',
        'view',
        'create',
        'update',
        'delete',
    ],
    'Player' => [
        'list',
        'view',
        'create',
        'update',
        'delete',
    ],
    'Matchday' => [
        'list',
        'view',
        'create',
        'update',
        'delete',
    ],
    'FeeType' => [
        'list',
        'view',
        'create',
        'update',
        'delete',
    ],
    'CompetitionType' => [
        'list',
        'view',
        'create',
        'update',
        'delete',
    ],
    'Transaction' => [
        'list',
        'view',
        'create',
        'update',
        'delete',
    ],

];
