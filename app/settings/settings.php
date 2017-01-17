<?php
$settings = [
    'settings' => [
        'displayErrorDetails' => false, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        'eloquent' => [
            'driver' => 'mysql',
    		'host' => 'localhost',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],

        // Renderer settings
        'renderer' => [
            'folders' => [
                APPLICATION_PATH . '/templates',
            ],
            'ext' => 'phtml',
            'autoescape' => false,
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // i18n
        'i18n' => [

            // when the target locale is missing a translation/ template this the
            // fallback locale to use (probably "en")
            'default_locale' => 'en',

            // this is the type of the translation files using by zend-i18n
            'type' => 'phparray',

            // where the translation files are stored
            'file_path' => APPLICATION_PATH . '/languages/',
        ],

        'auth' => [

            // this is the session namespace. apps that want to authenticate
            // using this auth app must configure their mwauth-client to match
            'namespace' => 'jt_sso__',

            // remember me cookie settings
            'auth_token' => [
                'cookie_name' => 'auth_token',
                'expire' => strtotime("+3 months", time()), // time in seconds from now, e.g. 1440 = 1h from now
                'path' => '/',
            ],

            // these are attributes that will be written to session
            'valid_attributes' => [
                'first_name',
                'last_name',
                // 'name',
                'email',
                'username',
                'name',
                'id',
                'facebook_id',
                // 'backend',
            ],

            'cookie_domain' => null,
        ],

        'mail' => [

            // directory where suppressed email files are written to in non-prod env
            'file_path' => APPLICATION_PATH . '/../storage/mail/',

            // reply to
            'reply_to' => 'noreply@sso.vagrant',
        ],
    ],
];

// local settings
$localSettingsPath = realpath(APPLICATION_PATH . '/settings/settings-' . APPLICATION_ENV . '.php');
if (file_exists($localSettingsPath)) {
    $settings = array_merge_recursive($settings, include $localSettingsPath);
}

return $settings;
