<?php

namespace YuraDev\NotifyDispatcher\Notification\Facades;

use App\Models\Businesse;
use App\Models\Order;
use App\Models\UserWorkoutSessionPivot;
use App\Models\WorkoutSession;
use App\Notifications\AdminCustomNotification;
use App\Notifications\InviteResultNotification;
use App\Notifications\UserDepositNotification;
use App\Notifications\UserFriendRequest;
use App\Notifications\UserFriendRequestAnswer;
use App\Notifications\UserWorkoutSessionInvite;
use App\Notifications\UserWorkoutSessionRequest;
use App\Notifications\WorkoutStatusChangeNotification;
use App\Notifications\UserSentMessageToChat;
use Musonza\Chat\Eventing\MessageWasSent;
use YuraDev\NotifyDispatcher\Notification\Dispatcher;

use App\Models\User;
use App\Notifications\TestNotification;
use Hootlex\Friendships\Models\Friendship;


class Manager
{
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function send($notification, $notifiable)
    {
        $this->dispatcher->send($notification, $notifiable);
    }

    public function sendToAll($notification, $notifiable)
    {
        $this->dispatcher->sendAll($notification, $notifiable);
    }

    public function testNotification(User $user, $message)
    {
        $notification = new TestNotification($message);
        $this->dispatcher->send($notification, $user);
    }

}
