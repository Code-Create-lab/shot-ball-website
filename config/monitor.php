<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System Monitor Access Password
    |--------------------------------------------------------------------------
    |
    | Gate for the /run-command system monitor. Must be at least 16 chars or
    | the monitor stays disabled (503). Read via config() so it keeps working
    | under `php artisan config:cache` (env() returns null when config is
    | cached). Remember to re-run config:cache after changing the .env value.
    |
    */

    'password' => env('ADMIN_PANEL_PASSWORD', ''),

];
