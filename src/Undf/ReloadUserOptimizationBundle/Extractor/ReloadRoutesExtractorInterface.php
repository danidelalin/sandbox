<?php

namespace Undf\ReloadUserOptimizationBundle\Extractor;

use Symfony\Component\HttpFoundation\Request;

/**
 * ReloadRoutesExtractorInterface interface.
 *
 * @author Dani Gonzalez <danigonzalezgonzalez@gmail.com>
 */
interface ReloadRoutesExtractorInterface
{

    /**
     * Returns an array of exposed routes where keys are the route names.
     *
     * @return array
     */
    public function getRoutes();

    /**
     * Returns the Base URL.
     *
     * @return string
     */
    public function getBaseUrl();

    /**
     * Get the route prefix to use, i.e. the language if JMSI18nRoutingBundle is active
     *
     * @var string $locale the request locale
     */
    public function getPrefix($locale);

    /**
     * Get the host from RequestContext
     *
     * @return string
     */
    public function getHost();

    /**
     * Get the scheme from RequestContext
     *
     * @return string
     */
    public function getScheme();

    /**
     * Returns an array of routing resources.
     *
     * @return array
     */
    public function getResources();

    /**
     * Returns an array of all Route objects for which session user should
     * be reloaded
     *
     * @return array
     */
    public function getRoutesToReload();
}
