<?php

namespace UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RegistrationListener implements EventSubscriberInterface {

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_INITIALIZE => 'onRegistrationInitialize',
        );
    }

    public function onRegistrationInitialize($event) {
        $user = $event->getUser();
        $request = $event->getRequest();

        switch($request->get('_route')) {
            case 'register_spectator':
                $user->addRole('ROLE_SPECTATOR');
            break;
        }
    }
}
