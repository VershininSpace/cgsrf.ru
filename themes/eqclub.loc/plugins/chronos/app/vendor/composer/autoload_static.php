<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita88855083ef3dd861bb5ee60e298f82a
{
    public static $prefixLengthsPsr4 = array (
        'Q' => 
        array (
            'Qiwi\\Api\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Qiwi\\Api\\' => 
        array (
            0 => __DIR__ . '/..' . '/qiwi/bill-payments-php-sdk/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Curl' => 
            array (
                0 => __DIR__ . '/..' . '/curl/curl/src',
            ),
        ),
    );

    public static $classMap = array (
        'YandexMoney\\API' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/api.php',
        'YandexMoney\\BaseAPI' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/base.php',
        'YandexMoney\\Config' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/base.php',
        'YandexMoney\\Exceptions\\APIException' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/exceptions.php',
        'YandexMoney\\Exceptions\\FormatError' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/exceptions.php',
        'YandexMoney\\Exceptions\\ScopeError' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/exceptions.php',
        'YandexMoney\\Exceptions\\ServerError' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/exceptions.php',
        'YandexMoney\\Exceptions\\TokenError' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/exceptions.php',
        'YandexMoney\\ExternalPayment' => __DIR__ . '/..' . '/yandex-money/yandex-money-sdk-php/lib/external_payment.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita88855083ef3dd861bb5ee60e298f82a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita88855083ef3dd861bb5ee60e298f82a::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInita88855083ef3dd861bb5ee60e298f82a::$prefixesPsr0;
            $loader->classMap = ComposerStaticInita88855083ef3dd861bb5ee60e298f82a::$classMap;

        }, null, ClassLoader::class);
    }
}
