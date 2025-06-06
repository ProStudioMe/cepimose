<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitf9a0c10d6df9f7b5fef232f74cadcb58
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitf9a0c10d6df9f7b5fef232f74cadcb58', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitf9a0c10d6df9f7b5fef232f74cadcb58', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitf9a0c10d6df9f7b5fef232f74cadcb58::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
