<?php

namespace Undf\ReloadUserOptimizationBundle\Matcher;

use Undf\ReloadUserOptimizationBundle\Extractor\ReloadRoutesExtractor;

class RouteMatcher
{

    /**
     * @var Undf\ReloadUserOptimizationBundle\Extractor\ReloadRoutesExtractor
     */
    private $extractor;
    private $container;

    public function __construct(ReloadRoutesExtractor $extractor, $container)
    {
        $this->extractor = $extractor;
        $this->container = $container;
    }

    public function getRequestRouteName()
    {
        return $this->container->get('request')->get('_route');
    }

    public function getRequestRouteMethod()
    {
        return $this->container->get('request')->getMethod();
    }

    public function shouldUserBeReloaded()
    {
        if($this->getRequestRouteMethod() == 'POST') {
            return true;
        }

        $routesToReload = array_keys($this->extractor->getRoutesToReload());
        return in_array($this->getRequestRouteName(), $routesToReload);
    }

}
