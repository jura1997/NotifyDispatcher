<?php

namespace YuraDev\NotifyDispatcher\Notification;

use App\Models\User;
use Illuminate\Notifications\Notification;
use YuraDev\NotifyDispatcher\NotificationChannels\GcmChannel;
use Predis\Client;

class Dispatcher
{
    private $gcm_notify = [];

    /**
     * Dispatch notification. 1st priority - web-socket channel.
     *
     * @param Notification $notification
     * @param $notifiable
     * @param string $channel
     */
    public function send(Notification $notification, $notifiable, $channel = '')
    {
        $this->process($notification, $notifiable, $channel);
        $this->checkGCMBasket($notification);
    }

    public function sendAll(Notification $notification, $users, string $channel= '')
    {
        $users = $users ?: User::get()->all();

        foreach ($users as $item) {

            $this->process($notification, $item, $channel);
        }
        $this->checkGCMBasket($notification);
    }

    public function sendOnlyGcm(Notification $notification, $notifiable)
    {
        $this->processOnlyGcm($notification, $notifiable);
        $this->checkGCMBasket($notification);
    }

    public function processOnlyGcm(Notification $notification, $notifiable)
    {
        if ($notifiable->checkDataForGcm() && !$this->isConnectedToWebSocket($notifiable)) {
            $notifiable->setChannels(GcmChannel::class)->notify($notification);
        }
        // if ($this->isConnectedToWebSocket($notifiable)){
        //     $notifiable->setChannels('broadcast')->notify($notification);
        // }
    }

    private function process(Notification $notification, $notifiable, string $channel = '')
    {
        if ($channel) {
            $notifiable->setChannels($channel)->notify($notification);
        }
        else{
            if ($notifiable->checkDataForGcm()) {
                $notifiable->setChannels(GcmChannel::class)->notify($notification);
            }
            if ($this->isConnectedToWebSocket($notifiable)){
                $notifiable->setChannels('broadcast')->notify($notification);
            }

            $notifiable->setChannels('database')->notify($notification);
        }

    }
    private function isConnectedToWebSocket($user)
    {

        $options = array(
            'cluster' => 'redis',
        );
        $parameters = array('tcp://127.0.0.1/');
        $client = new Client(
            $parameters
            , $options
            );

        try {
            $value = $client->get('presence-besoon:members');
            $online_users = json_decode($value, true) ?? [];
        }
        catch (\Exception $e) {

            return false;
        }

        return in_array($user->id, array_pluck($online_users, 'user_id'));
    }

    private function checkGCMBasket(Notification $notification)
    {
        if (!empty($this->gcm_notify)) {

            (new DeviceCollection($this->gcm_notify))->setChannels(GcmChannel::class)->notify($notification);
        }
    }

}
