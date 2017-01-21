<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {

    // we will add folders after instatiation so that we can assign IDs
    $settings = $c->get('settings')['renderer'];
    $folders = $settings['folders'];
    unset($settings['folders']);

    $engine = \Foil\engine($settings);

    // assign IDs
    foreach($folders as $id => $folder) {
        if (is_numeric($id)) {
            $engine->addFolder($folder);
        } else {
            $engine->addFolder($folder, $id);
        }
    }

    $engine->registerFunction('translate', new \App\View\Helper\Translate($c) );
    $engine->registerFunction('pathFor', new \App\View\Helper\PathFor($c) );
    $engine->registerFunction('generateSortQuery', new \App\View\Helper\GenerateSortQuery($c) );

    return $engine;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// locale - required by a few services, so easier to put in container
$container['locale'] = function($c) use ($app) {
    $settings = $c->get('settings')['i18n'];
    $locale = $c['request']->getCookieParam('language', $settings['default_locale']);

    return $locale;
};

// i18n
$container['i18n'] = function($c) {

    $settings = $c->get('settings')['i18n'];

    // get the language code from the cookie, then get the language file
    // if no language file, or no cookie even, get default language.
    $locale = $c['locale'];
    $type = $settings['type'];
    $filePath = $settings['file_path'];
    $pattern = '/%s.php';
    $textDomain = 'default';

    $translator = new \Zend\I18n\Translator\Translator();
    $translator->addTranslationFilePattern($type, $filePath, $pattern, $textDomain);
    $translator->setLocale($locale);
    $translator->setFallbackLocale($settings['default_locale']);

    return $translator;
};

$container['auth'] = function ($c) {
    $settings = $c->get('settings')['auth'];
    $authAdapter = new \App\Auth\Adapter\Eloquent( $c['model.user'] );
    return new \App\Auth\Auth($authAdapter, $settings);
};

// flash
$container['flash'] = function ($c) {
    return new \MartynBiz\FlashMessage\Flash();
};

// mail
$container['mail_manager'] = function ($c) {
    $settings = $c->get('settings')['mail'];

    // if not in production, we will write to file
    if (APPLICATION_ENV == 'production') {
        $transport = new Zend\Mail\Transport\Sendmail();
    } else {
        $transport = new \Zend\Mail\Transport\File();
        $options   = new \Zend\Mail\Transport\FileOptions(array(
            'path' => realpath($settings['file_path']),
            'callback' => function (\Zend\Mail\Transport\File $transport) {
                return 'Message_' . microtime(true) . '_' . mt_rand() . '.txt';
            },
        ));
        $transport->setOptions($options);
    }

    $locale = $c['locale'];
    $defaultLocale = @$c->get('settings')['i18n']['default_locale'];

    return new \App\Mail\Manager($transport, $c['renderer'], $locale, $defaultLocale, $c['i18n']);
};



use League\OAuth2\Server\AuthorizationServer;

use App\OAuth2\Repositories\SessionRepository;
use App\OAuth2\Repositories\AccessTokenRepository;
use App\OAuth2\Repositories\RefreshTokenRepository;
use App\OAuth2\Repositories\ClientRepository;
use App\OAuth2\Repositories\ScopeRepository;
use App\OAuth2\Repositories\AuthCodeRepository;
use League\OAuth2\Server\Grant\AuthCodeGrant;
// use App\OAuth2\Grant\RefreshTokenGrant;
// use App\OAuth2\Grant\ClientCredentialsGrant;

// A server which issues access tokens after successfully authenticating a client
// and resource owner, and authorizing the request.
$container['authorization_server'] = function ($c) {

    // Init our repositories
    $clientRepository = new ClientRepository();
    $scopeRepository = new ScopeRepository();
    $accessTokenRepository = new AccessTokenRepository();
    $authCodeRepository = new AuthCodeRepository();
    $refreshTokenRepository = new RefreshTokenRepository();

    $privateKey = realpath(APPLICATION_PATH . '/../storage/private.pem');
    $publicKey = realpath(APPLICATION_PATH . '/../storage/public.pem');

    // Setup the authorization server
    $server = new AuthorizationServer(
        $clientRepository,
        $accessTokenRepository,
        $scopeRepository,
        $privateKey,
        $publicKey
    );

    $grant = new AuthCodeGrant(
        $authCodeRepository,
        $refreshTokenRepository,
        // authorization codes will expire after 10 minutes
        new \DateInterval('PT10M')
    );
    // refresh tokens will expire after 1 month
    $grant->setRefreshTokenTTL(new \DateInterval('P1M'));

    // Enable the authentication code grant on the server
    $server->enableGrantType(
        $grant,
        // access tokens will expire after 1 hour
        new \DateInterval('PT1H')
    );

    return $server;

    // $server = new \League\OAuth2\Server\AuthorizationServer();
    //
    // // the oauth2-server we're using requires these objects for managing storage
    // // of oauth items such as tokens
    // $server->setSessionStorage(new \App\OAuth2\Storage\SessionStorage());
    // $server->setAccessTokenStorage(new \App\OAuth2\Storage\AccessTokenStorage());
    // $server->setRefreshTokenStorage(new \App\OAuth2\Storage\RefreshTokenStorage());
    // $server->setClientStorage(new \App\OAuth2\Storage\ClientStorage());
    // $server->setScopeStorage(new \App\OAuth2\Storage\ScopeStorage());
    // $server->setAuthCodeStorage(new \App\OAuth2\Storage\AuthCodeStorage());
    //
    // // add a couple of grants for this server
    // $server->addGrantType( new \App\OAuth2\Grant\AuthCodeGrant() );
    // $server->addGrantType( new \App\OAuth2\Grant\RefreshTokenGrant() );
    // $server->addGrantType( new \App\OAuth2\Grant\ClientCredentialsGrant() );
    //
    // return $server;
};

// A server which sits in front of protected resources
// (for example “tweets”, users’ photos, or personal data)
// and is capable of accepting and responsing to protected
// resource requests using access tokens.
$container['resource_server'] = function ($c) {

    $sessionStorage = new \App\OAuth2\Storage\SessionStorage();
    $accessTokenStorage = new \App\OAuth2\Storage\AccessTokenStorage();
    $clientStorage = new \App\OAuth2\Storage\ClientStorage();
    $scopeStorage = new \App\OAuth2\Storage\ScopeStorage();

    $server = new \League\OAuth2\Server\ResourceServer(
        $sessionStorage,
        $accessTokenStorage,
        $clientStorage,
        $scopeStorage
    );

    return $server;
};


// Models

// initiate database connection
// setup eloquent for the job
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->get('settings')['eloquent']);
// $capsule->setEventDispatcher( new \Illuminate\Events\Dispatcher( new \Illuminate\Container\Container ));
$capsule->bootEloquent();
$capsule->setAsGlobal();

$container['model.user'] = function ($c) {
    return new \App\Model\User();
};

$container['model.meta'] = function ($c) {
    return new \App\Model\Meta();
};

$container['model.auth_token'] = function ($c) {
    return new \App\Model\AuthToken();
};

$container['model.recovery_token'] = function ($c) {
    return new \App\Model\RecoveryToken();
};
