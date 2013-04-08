<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Undf\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RouterInterface;
use Lunetics\LocaleBundle\Validator\MetaValidator;
use FOS\UserBundle\Model\UserManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller managing the user profile
 */
class LocaleController extends Controller
{

    /**
     * Router Service
     * @var Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * MetaValidator for locales
     * @var Lunetics\LocaleBundle\Validator\MetaValidator
     */
    private $metaValidator;

    /**
     * Entity manager
     * @var FOS\UserBundle\Model\UserManagerInterface
     */
    private $user_manager;

    /**
     * From Config
     * @var string
     */
    private $useReferrer;

    /**
     * From Config
     * @var string
     */
    private $redirectToRoute;

    /**
     * From Config
     * @var string
     */
    private $statusCode;

    private function initialize()
    {
        $this->router = $this->get('router');
        $this->metaValidator = $this->get('lunetics_locale.validator.meta');
        $this->user_manager = $this->get('undf_user.user_manager');
        $this->useReferrer = $this->container->getParameter('lunetics_locale.switcher.use_referrer', true);
        $this->redirectToRoute = $this->container->getParameter('lunetics_locale.switcher.redirect_to_route', null);
        $this->statusCode = $this->container->getParameter('lunetics_locale.switcher.redirect_statuscode', 302);
    }

    /**
     * Action for locale switch
     *
     * @param Request $request
     *
     * @throws \InvalidArgumentException
     * @return RedirectResponse
     */
    public function switchAction(Request $request)
    {
        $this->initialize();

        $_locale = $request->attributes->get('_locale', $request->getLocale());
        $statusCode = $request->attributes->get('statusCode', $this->statusCode);
        $useReferrer = $request->attributes->get('useReferrer', $this->useReferrer);
        $redirectToRoute = $request->attributes->get('route', $this->redirectToRoute);

        $metaValidator = $this->metaValidator;
        if (!$metaValidator->isAllowed($_locale)) {
            throw new \InvalidArgumentException(sprintf('Not allowed to switch to locale %s', $_locale));
        }

        // Update user with the new locale
        if($user = $this->getUser()) {
            $this->user_manager->updateUserLocale($user, $_locale);
        }

        // Redirect the User
        if ($useReferrer && $request->headers->has('referer')) {
            $response = new RedirectResponse($request->headers->get('referer'), $statusCode);
        } elseif ($this->router && $redirectToRoute) {
            $response = new RedirectResponse($this->router->generate($redirectToRoute, array('_locale' => $_locale)), $statusCode);
        } else {
            // TODO: this seems broken, as it will not handle if the site runs in a subdir
            // TODO: also it doesn't handle the locale at all and can therefore lead to an infinite redirect
            $response = new RedirectResponse($request->getScheme() . '://' . $request->getHttpHost() . '/', $statusCode);
        }

        return $response;
    }

}
