<?php

return [
    'GET' => [
        '/' => 'SiteHandler@index',
        '/login' => 'SiteHandler@login',
        '/register' => 'SiteHandler@register',
        '/logout' => 'AuthHandler@logout',
        '/upload' => 'SiteHandler@upload',
        '/files' => 'SiteHandler@files',
    ],
    'POST' => [
        '/login' => 'AuthHandler@login',
        '/register' => 'AuthHandler@register',
        '/upload' => 'FileHandler@upload',
    ]
];