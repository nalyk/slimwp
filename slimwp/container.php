<?php

use DI\Container;
use Slimwp\Core\Middleware\TranslatorMiddleware;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Nyholm\Psr7\getParsedBody;
use Odan\Twig\TwigAssetsExtension;
use Odan\Twig\TwigTranslationExtension;
use Psr\Log\LoggerInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Flash\Messages;
use Slim\Interfaces\RouteParserInterface;
use Slim\Views\Twig;
use Symfony\Component\Translation\Formatter\MessageFormatter;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Translation\Loader\MoFileLoader;
use Symfony\Component\Translation\Translator;
use Twig\Loader\FilesystemLoader;
use voku\helper\AntiXSS;
use Phpfastcache\CacheManager;
use Phpfastcache\Drivers\Redis\Config;

$container->set('settings', function() {
    return require_once(__ROOT__ . '/slimwp/settings.php');
});

$container->set(LoggerInterface::class, function (Container $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Logger($settings['name']);
    $processor = new UidProcessor();
    $logger->pushProcessor($processor);
    $handler = new StreamHandler($settings['path'], $settings['level']);
    $logger->pushHandler($handler);
    return $logger;
});

$container->set('antixss', function () {
    return new AntiXSS();
});


$container->set('flash', function () {
    return new \Slim\Flash\Messages();
});


$container->set('routerParser', $app->getRouteCollector()->getRouteParser());


$container->set('view', function (Container $c) {
    $twig = Twig::create(__ROOT__ . '/slimwp/', $c->get('settings')['twig']);
    $loader = $twig->getLoader();
    $loader->addPath(__ROOT__ . '/public', 'public');
    $twig->addExtension(new TwigAssetsExtension($twig->getEnvironment(), (array)$c->get('settings')['assets']));
    $twig->addExtension(new TwigTranslationExtension());
    $twig->addExtension(new \Twig\Extension\DebugExtension());
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    $twig->addExtension(new Twig_Extensions_Extension_Intl());
    $twig->addExtension(new Twig_Extensions_Extension_Date());
    return $twig;
});


$container->set(Translator::class, static function (Container $container) {
    $settings = $container->get('settings')['locale'];
    $translator = new Translator(
        $settings['locale'],
        new MessageFormatter(new IdentityTranslator()),
        $settings['cache']
    );
    $translator->addLoader('mo', new MoFileLoader());
    __($translator); // Set translator instance
    return $translator;
});


$container->set(TranslatorMiddleware::class, static function (Container $container) {
    $settings = $container->get('settings')['locale'];
    $localPath = $settings['path'];
    $translator = $container->get(Translator::class);
    return new TranslatorMiddleware($translator, $localPath);
});


// *************************************************
// Slimwp CLASSES ***********************************
// ************************************************* 

// Form-data validation helper (send validation results
// via session to the original form upon failure)
$container->set('validator', function (Container $c) {
   return new Slimwp\Core\Classes\Validation\Validator;
});

$container->set('redis', function (Container $c) {
    $InstanceCache = CacheManager::getInstance('redis', new Config([
      'host' => '127.0.0.1', //Default value
      'port' => 6379
    ]));
   return $InstanceCache;
});
