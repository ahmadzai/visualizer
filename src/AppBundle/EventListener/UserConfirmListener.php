<?php
/**
 * Created by PhpStorm.
 * User: wakhan
 * Date: 1/9/2018
 * Time: 2:42 PM
 */

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;

class UserConfirmListener implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => ['stopEvent', 9999],
            FOSUserEvents::REGISTRATION_CONFIRMED => ['stopEvent', 9999],

        );;


    }

    public function stopEvent(FilterUserResponseEvent $event) {

        $event->stopPropagation();
    }

}