<?php

namespace YuraDev\NotifyDispatcher\NotificationChannels;

use Exception;
use Illuminate\Events\Dispatcher;
use YuraDev\NotifyDispatcher\NotificationChannels\Client;
use Illuminate\Notifications\Notification;
use YuraDev\NotifyDispatcher\NotificationChannels\Exceptions\SendingFailed;
use Illuminate\Notifications\Events\NotificationFailed;
class GcmChannel
{
    /**
     * The GCM client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * Create a new channel instance.
     *
     * @param Client $client
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function __construct(Client $client, Dispatcher $events)
    {
        $gcmConfig = config('broadcasting.connections.gcm');
        // dd($gcmConfig);
        $client = new Client();
        $client->setApiKey($gcmConfig['key']);
        // $client->getHttpClient()->setAdapter(new Curl());
        $this->client =  $client;
        $this->events = $events;
    }

    /**
     * Send the notification to Google Cloud Messaging.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     * @throws Exceptions\SendingFailed
     */
    public function send($notifiable, Notification $notification)
    {
        $tokens = (array) $notifiable->routeNotificationFor('gcm', $notification);
        if (empty($tokens)) {
            return;
        }

        // dd($notification->toGcm($notifiable));
        $message = $notification->toGcm($notifiable);
        if (! $message) {
            return;
        }

        $packet = $this->getPacket($tokens, $message);
        // dd($packet);
        try {
            $response = $this->client->send($packet);
            // dd($response);
        } catch (Exception $exception) {
            throw SendingFailed::create($exception);
        }

        // if (! $response->getFailureCount() == 0) {
        //     $this->handleFailedNotifications($notifiable, $notification, $response);
        // }
    }


    /**
     * @param $tokens
     * @param $message
     * @return \NotificationChannels\Gcm\Packet
     */
    protected function getPacket($tokens, $message)
    {
        $data = [];
        $data['registration_ids']=$tokens;


        if($message->custom_flag){
            $data['data'] =  $message->data;
        }else{
            $data['setCollapseKey'] = $message->title;
            $data['data'] =  $message->data;

            $data['notification'] =[
                'title' => $message->title,
                'body' => $message->message,
                'sound' => $message->sound,
                'content_available'=>true
            ];
        }
        return $data;
    }

    /**
     * Handle a failed notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @param $response
     */
    protected function handleFailedNotifications($notifiable, Notification $notification, $response)
    {
        $results = $response->getResults();

        foreach ($results as $token => $result) {
            if (! isset($result['error'])) {
                continue;
            }

            $this->events->dispatch(
                new NotificationFailed($notifiable, $notification, get_class($this), [
                    'token' => $token,
                    'error' => $result['error'],
                ])
            );
        }
    }
}
