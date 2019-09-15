<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Encryption Keys
    |--------------------------------------------------------------------------
    |
    | Passport uses encryption keys while generating secure access tokens for
    | your application. By default, the keys are stored as local files but
    | can be set via environment variables when that is more convenient.
    |
    */

    'private_key' => env('PASSPORT_PRIVATE_KEY'),

    'public_key' => env('PASSPORT_PUBLIC_KEY'),

    'personal_access_client_id' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_ID'),

    'personal_access_client_secret' => env('PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET'),

    'password_grant_client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),

    'password_grant_client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),

];
