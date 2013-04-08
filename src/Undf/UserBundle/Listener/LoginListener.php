<?php

namespace Undf\UserBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @var string
     */
    private $sessionVar;

    public function __construct($sessionVar)
    {
        $this->sessionVar = $sessionVar;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $session = $event->getRequest()->getSession();
        $session->set($this->sessionVar, $token->getUser()->getLocale());
    }

}