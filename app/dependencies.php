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
