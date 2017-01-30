<?php

namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{

    private static $handleContainer;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        static::$handleContainer = $container;
    }

    /**
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        return static::$handleContainer;
    }

    public static function service ($service_name)
    {
        return static::$handleContainer ->get($service_name);
    }

    public static function paramater($param)
    {
        return static::getContainer()->getParameter($param);
    }

}