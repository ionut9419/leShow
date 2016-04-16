<?php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ChangePasswordListener implements EventSubscriberInterface {

    private $router;
    
    public function __construct(Router $router) {
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess',
        );
    }

    public function onChangePasswordSuccess($event) {
        $redirectTo = $this->router->generate('fos_user_change_password');
        $event->setResponse(new RedirectResponse($redirectTo));
    }
}
