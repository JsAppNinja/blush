<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit82c5d27ae5007b0935f7dae786f30fa6
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        'c964ee0ededf28c96ebd9db5099ef910' => __DIR__ . '/..' . '/guzzlehttp/promises/src/functions_include.php',
        'a0edc8309cc5e1d60e3047b5df6b7052' => __DIR__ . '/..' . '/guzzlehttp/psr7/src/functions_include.php',
        '37a3dc5111fe8f707ab4c132ef1dbc62' => __DIR__ . '/..' . '/guzzlehttp/guzzle/src/functions_include.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Component\\Yaml\\' => 23,
            'Symfony\\Component\\Filesystem\\' => 29,
            'Symfony\\Component\\EventDispatcher\\' => 34,
            'Symfony\\Component\\Debug\\' => 24,
            'Symfony\\Component\\Console\\' => 26,
            'Symfony\\Component\\Config\\' => 25,
            'Stripe\\' => 7,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Http\\Message\\' => 17,
            'Phinx\\' => 6,
        ),
        'O' => 
        array (
            'OpenTok\\' => 8,
        ),
        'G' => 
        array (
            'GuzzleHttp\\Psr7\\' => 16,
            'GuzzleHttp\\Promise\\' => 19,
            'GuzzleHttp\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'Symfony\\Component\\Filesystem\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/filesystem',
        ),
        'Symfony\\Component\\EventDispatcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/event-dispatcher',
        ),
        'Symfony\\Component\\Debug\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/debug',
        ),
        'Symfony\\Component\\Console\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/console',
        ),
        'Symfony\\Component\\Config\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/config',
        ),
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Phinx\\' => 
        array (
            0 => __DIR__ . '/..' . '/robmorgan/phinx/src/Phinx',
        ),
        'OpenTok\\' => 
        array (
            0 => __DIR__ . '/..' . '/opentok/opentok/src/OpenTok',
        ),
        'GuzzleHttp\\Psr7\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/psr7/src',
        ),
        'GuzzleHttp\\Promise\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/promises/src',
        ),
        'GuzzleHttp\\' => 
        array (
            0 => __DIR__ . '/..' . '/guzzlehttp/guzzle/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Mailgun\\Tests' => 
            array (
                0 => __DIR__ . '/..' . '/mailgun/mailgun-php/tests',
            ),
            'Mailgun' => 
            array (
                0 => __DIR__ . '/..' . '/mailgun/mailgun-php/src',
            ),
        ),
        'J' => 
        array (
            'JohnStevenson\\JsonWorks' => 
            array (
                0 => __DIR__ . '/..' . '/aoberoi/json-works/src',
            ),
        ),
        'G' => 
        array (
            'Guzzle\\Tests' => 
            array (
                0 => __DIR__ . '/..' . '/guzzle/guzzle/tests',
            ),
            'Guzzle' => 
            array (
                0 => __DIR__ . '/..' . '/guzzle/guzzle/src',
            ),
        ),
    );

    public static $classMap = array (
        'Plivo\\Conference' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\DTMF' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Dial' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Element' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\GetDigits' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Hangup' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Message' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Number' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Play' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\PlivoError' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\PreAnswer' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Record' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Redirect' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Response' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\RestAPI' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Speak' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\User' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
        'Plivo\\Wait' => __DIR__ . '/..' . '/plivo/plivo-php/plivo.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit82c5d27ae5007b0935f7dae786f30fa6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit82c5d27ae5007b0935f7dae786f30fa6::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit82c5d27ae5007b0935f7dae786f30fa6::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit82c5d27ae5007b0935f7dae786f30fa6::$classMap;

        }, null, ClassLoader::class);
    }
}