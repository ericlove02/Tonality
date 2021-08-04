<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit91721f5c01f1cda29e8f232d7fd828b0
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SpotifyWebAPI\\' => 14,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SpotifyWebAPI\\' => 
        array (
            0 => __DIR__ . '/..' . '/jwilsson/spotify-web-api-php/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit91721f5c01f1cda29e8f232d7fd828b0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit91721f5c01f1cda29e8f232d7fd828b0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit91721f5c01f1cda29e8f232d7fd828b0::$classMap;

        }, null, ClassLoader::class);
    }
}
