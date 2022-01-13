<?php

namespace App\EventSubscriber;

use App\Event\UserRegisteredEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    public function onUserRegistered(UserRegisteredEvent $event)
    {
        $mail = [
            'from' =>'team@where-to-eat.co',
            'to' => $event->getUser()->getEmail(),
            'subject' => $event->getUser()->getFirstName(). ' ,Welcome to where-to-eat',
            'body' => $event->getUser()->getFirstName(). ',You are now a member of our community'
        ];

        dump($mail);
    }

    public static function getSubscribedEvents()
    {
        return [
            'user_registered' => 'onUserRegistered',
        ];
    }
}
