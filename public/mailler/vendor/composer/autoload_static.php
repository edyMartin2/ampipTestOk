<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit95d0ff735ce88d85745978c939bbd8cc
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit95d0ff735ce88d85745978c939bbd8cc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit95d0ff735ce88d85745978c939bbd8cc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
