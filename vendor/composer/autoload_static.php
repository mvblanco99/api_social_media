<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit045e27221f34dab316d9f94d71414c0b
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'A' => 
        array (
            'Api\\ApiUtilities\\' => 17,
            'Api\\ApiUsers\\' => 13,
            'Api\\ApiRoutes\\' => 14,
            'Api\\ApiReactions\\' => 17,
            'Api\\ApiPosts\\' => 13,
            'Api\\ApiConnection\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'Api\\ApiUtilities\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/utilities',
        ),
        'Api\\ApiUsers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/users',
        ),
        'Api\\ApiRoutes\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/routes',
        ),
        'Api\\ApiReactions\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/reactions',
        ),
        'Api\\ApiPosts\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/posts',
        ),
        'Api\\ApiConnection\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/connection',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit045e27221f34dab316d9f94d71414c0b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit045e27221f34dab316d9f94d71414c0b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit045e27221f34dab316d9f94d71414c0b::$classMap;

        }, null, ClassLoader::class);
    }
}
