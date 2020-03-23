<?php
declare(strict_types=1);

return [

    /***********************************************************
     * OPTIONS THAT WILL MOST LIKELY CHANGE
     **********************************************************/

    // Slim
    'displayErrorDetails' => true, // Set to false in production
    'logErrors' => true,
    'logErrorDetails' => true,
    'debugEngine' => 'Whoops', // Error | Whoops

    // Slimwp globals
    'slimwp' => [
        'timezone' => 'Europe/Chisinau',
        'wphost' => ''
    ],

    // Database
    'db' => [
        'host' => 'db_host',
        'database' => 'db_name',
        'username' => 'db_user',
        'password' => 'db_pass',
        'charset' => ' utf8mb4',
        'collation' => ' utf8mb4_unicode_ci'
    ],

    // Monolog
    'logger' => [
        'name' => 'slimwp',
        'path' =>  __ROOT__ . '/private/log/app.log',
        'level' => \Monolog\Logger::DEBUG,
    ],

    // Cryptography keys
    'crypto' => [
        'mail' => 'mail-encryption-key',
        'reqparams' => 'reqparams-encryption-key'
    ],

    // Api keys
    // TODO: get this out of the config
    // see https://www.codementor.io/@ccornutt/keeping-credentials-secure-in-php-kvcbrk55z
    'apis' => [
        'google' => '',
        'facebook' => '',
        'aliexpress' => '',
        'matrix' => '',
        'mailtrain' => '',
        'twilio' => '',
    ],

    /***********************************************************
     * OPTIONS TO TWEAK ONLY IF YOU REALLY NEED TO / KNOW HOW TO
     **********************************************************/

    'php' => [
        /** 
         * password_hash() configuration.
         */
        'password_hash_algo' => PASSWORD_ARGON2ID,
        'password_hash_opts' => [ 
            'memory_cost' => 2 * PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
            'time_cost' => 2 * PASSWORD_ARGON2_DEFAULT_TIME_COST,
            'threads' => PASSWORD_ARGON2_DEFAULT_THREADS 
        ],
        /**
         * Session cookies configuration (consumed by the @see
         * SessionMiddleware). Changing these defaults may compromise
         * security (i.e. break CSRF protection). See 
         * @link https://scotthelme.co.uk/csrf-is-really-dead/.
         */
        'session_cookie_lifetime' => 0,
        'session_cookie_secure' => true,
        'session_cookie_httponly' => true,
        'session_cookie_samesite' => 'Lax'
    ],

    'headers' => [
        /**
         * Feature-policy http header configuration (consumed by the 
         * @see HeadersMiddleware). Changing these defaults may compromise
         * security (i.e. enable unwanted browser apis/features). See 
         * @link https://scotthelme.co.uk/a-new-security-header-feature-policy/
         */ 
        'feature-policy' => [
            'geolocation' => "'self'",
            'midi' => "'self'",
            'notifications' => "'self'",
            'push' => "'self'",
            'sync-xhr' => "'self'",
            'microphone' => "'self'",
            'camera' => "'self'",
            'magnetometer' => "'self'",
            'gyroscope' => "'self'",
            'speaker' => "'self'",
            'vibrate' => "'self'",
            'fullscreen' => "'self'",
            'payment' => "'self'",
        ],

        /**
         * Referrer-policy and content-type-options http header configuration
         * (consumed by the @see HeadersMiddleware). Changing these defaults
         * may compromise security. See 
         * https://scotthelme.co.uk/a-new-security-header-referrer-policy/
         * https://scotthelme.co.uk/hardening-your-http-response-headers/#x-content-type-options
         */ 
        'referrer-policy' => 'strict-origin-when-cross-origin',
        'content-type-options' => 'nosniff',
        // TODO remove unsafe-eval once odan/twig-assets works with csp
        'csp' => [
            'script-src' => [ 'self' => true, 'allow' => [ $_SERVER['SERVER_NAME'] ], 'strict-dynamic' => true, 'unsafe-eval' => true ],
            'object-src' => [ 'default-src' => 'false' ],
            'frame-ancestors' => [ 'self' => true, 'allow' => [ 'https://' . $_SERVER['SERVER_NAME'] ] ],
            'base-uri' => 'self',
            'require-trusted-types-for' => 'script' // TODO not yet supported https://github.com/paragonie/csp-builder/issues/47
        ],

        /** Optimal production hsts values (see https://hstspreload.org/
         * before setting things up this)
         *   'enable' => false,
         *   'max-age' => 31536000,
         *   'include-sub-domains' => true,
         *   'preload' => true,
         */
        'hsts' => [
            'enable' => true,
            'max-age' => 15552,//552000,
            'include-sub-domains' => false,
            'preload' => false,
        ]
    ],

    /***********************************************************
     * OPTIONS THAT YOU SHOULDN'T HAVE A REASON TO TOUCH UNLESS
     * YOU ARE A GLUED DEVELOPER
     **********************************************************/

    // Twig (set 'cache' to false to disable caching)
    'twig' => [
        'cache' => __ROOT__ . '/private/cache/twig',
        'auto_reload' => true,
        'debug' => true
    ],

    // Twig-translation
    'locale' => [
        'path' => __ROOT__ . '/private/locale',
        'cache' => __ROOT__ . '/private/cache/locale',
        'locale' => 'en_US',
        'domain' => 'messages',
    ],

    // Odan-assets
    'assets' => [
        'path' => __ROOT__ . '/public/assets/cache',
        'url_base_path' => '/assets/cache/',
        // Cache settings
        'cache_enabled' => true,
        'cache_path' => __ROOT__ . '/private/cache',
        'cache_name' => 'assets',
        // Enable JavaScript and CSS compression
        'minify' => 1,
    ]

];
