<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitbab142cf79df54cc7ec85bc8780e3fd6
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

        spl_autoload_register(array('ComposerAutoloaderInitbab142cf79df54cc7ec85bc8780e3fd6', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitbab142cf79df54cc7ec85bc8780e3fd6', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitbab142cf79df54cc7ec85bc8780e3fd6::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}