<?php

namespace YuraDev\NotifyDispatcher\Listeners;

use App\Models\User;
use YuraDev\NotifyDispatcher\Notification\Facades\Manager;;

/**
 * @property Manager $notificationService
 */
class NotificationSender
{
    private $notificationService;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Manager $manager)
    {
        $this->notificationService = $manager;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array
     */
    public function subscribe($events)
    {

    }

    public function send($notification, $user)
    {
        $this->notificationService->send($notification, $user);
    }

    public function sendToAll($notification, $users)
    {
        $this->notificationService->sendToAll($notification, $users);
    }

}
