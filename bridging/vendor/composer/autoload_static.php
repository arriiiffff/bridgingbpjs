<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit78b4b0ec3d5ce113832d9c5e5c0cb32a
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'LZCompressor\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'LZCompressor\\' => 
        array (
            0 => __DIR__ . '/..' . '/nullpunkt/lz-string-php/src/LZCompressor',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit78b4b0ec3d5ce113832d9c5e5c0cb32a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit78b4b0ec3d5ce113832d9c5e5c0cb32a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit78b4b0ec3d5ce113832d9c5e5c0cb32a::$classMap;

        }, null, ClassLoader::class);
    }
}
