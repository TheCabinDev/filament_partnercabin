<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | Daftar path yang diizinkan untuk diakses dari domain luar.
    | Misalnya semua endpoint API dan cookie Sanctum.
    |
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | Method HTTP apa saja yang boleh (GET, POST, PUT, DELETE, dll).
    |
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Domain frontend yang boleh akses API kamu.
    | Untuk Next.js di local, biasanya http://localhost:3000
    |
    */
    'allowed_origins' => ['http://localhost:3000'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | Header yang boleh dikirim dari client.
    |
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | Header tambahan yang boleh dibaca di frontend.
    |
    */
    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | Waktu (dalam detik) browser cache hasil preflight request.
    |
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Set ke true kalau kamu pakai cookie/session (misal Sanctum).
    |
    */
    'supports_credentials' => true,

];
