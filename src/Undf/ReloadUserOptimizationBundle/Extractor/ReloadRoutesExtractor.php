<?php

namespace Undf\ReloadUserOptimizationBundle\Extractor;

use Symfony\Component\Routing\RouterInterface;
use JMS\I18nRoutingBundle\Router\I18nLoader;

/**
 * @author Dani Gonzalez <danigonzalezgonzalez@gmail.com>
 */
class ReloadRoutesExtractor implements ReloadRoutesExtractorInterface
{

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var array
     */
    protected $bundles;

    /**
     * @var array
     */
    protected $routesToReload;

    /**
     * Default constructor.
     *
     * @param RouterInterface $router         The router.
     * @param array           $bundles        list of loaded bundles to check when generating the prefix
     */
    public function __construct(RouterInterface $router, array $routesToReload = array(), $bundles = array())
    {
        $this->router = $router;
        $this->routesToReload = $routesToReload;
        $this->bundles = $bundles;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutes()
    {
        $exposedRoutes = array();
        foreach ($this->getReloadRoutes() as $name => $route) {
            // Maybe there is a better way to do that...
            $compiledRoute = $route->compile();
            $defaults = array_intersect_key(
                    $route->getDefaults(), array_fill_keys($compiledRoute->getVariables(), null)
            );
            $requirements = $route->getRequirements();
            $exposedRoutes[$name] = new ReloadRoute(
                            $compiledRoute->getTokens(),
                            $defaults,
                            $requirements
            );
        }

        return $exposedRoutes;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoutesToReload()
    {
        $routes = array();
        $collection = $this->router->getRouteCollection();
        $pattern = $this->buildPattern();

        foreach ($collection->all() as $name => $route) {
            if (false === $route->getOption('reload')) {
                continue;
            }

            if (($route->getOption('reload') && (true === $route->getOption('reload') || 'true' === $route->getOption('reload')))
                    || ('' !== $pattern && preg_match('#' . $pattern . '#', $name))) {
                $routes[$name] = $route;
            }
        }

        return $routes;
    }

    /**
     * {@inheritDoc}
     */
    public function getBaseUrl()
    {
        return $this->router->getContext()->getBaseUrl() ? : '';
    }

    /**
     * {@inheritDoc}
     */
    public function getPrefix($locale)
    {
        if (isset($this->bundles['JMSI18nRoutingBundle'])) {
            return $locale . I18nLoader::ROUTING_PREFIX;
        }

        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function getHost()
    {
        return $this->router->getContext()->getHost();
    }

    /**
     * {@inheritDoc}
     */
    public function getScheme()
    {
        return $this->router->getContext()->getScheme();
    }

    /**
     * {@inheritDoc}
     */
    public function getResources()
    {
        return $this->router->getRouteCollection()->getResources();
    }

    /**
     * Convert the routesToExpose array in a regular expression pattern.
     *
     * @return string
     */
    protected function buildPattern()
    {
        $patterns = array();
        foreach ($this->routesToReload as $toExpose) {
            $patterns[] = '(' . $toExpose . ')';
        }

        return implode($patterns, '|');
    }

}
