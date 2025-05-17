<?php

return [
    'avatar_column' => 'avatar_url',
    'disk' => env('FILESYSTEM_DISK', 'public'),
    'visibility' => 'true', // or replace by filesystem disk visibility with fallback value
];
