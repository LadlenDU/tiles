<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbe4ecca6885a0461562591190df7ae8f
{
    public static $prefixLengthsPsr4 = array (
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbe4ecca6885a0461562591190df7ae8f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbe4ecca6885a0461562591190df7ae8f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}