<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it, or make something more sophisticated.
putenv('USE_INTL_ICU_DATA_VERSION=true');
require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

// if you want to use the SonataPageBundle with multisite
// using different relative paths, you must change the request
// object to use the SiteRequest
// use Sonata\PageBundle\Request\SiteRequest as Request;

use Symfony\Component\HttpFoundation\Request;

$kernel->handle(Request::createFromGlobals())->send();
