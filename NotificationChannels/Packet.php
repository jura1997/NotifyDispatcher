<?php

namespace YuraDev\NotifyDispatcher\NotificationChannels;

use Zend\Json\Json;
use ZendService\Google\Gcm\Message;

class Packet extends Message
{
    /**
     * @var array
     */
    protected $notification;

    /**
     * Set the notification.
     *
     * @param array $notification
     * @return Message
     */
    public function setNotification(array $notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * To JSON
     * Utility method to put the JSON into the
     * GCM proper format for sending the message.
     *
     * @return string
     */
    public function toJson()
    {
        $json = [];

        dd(1);
        if (! empty($this->registrationIds)) {
           // $json['to'] = $this->registrationIds[0];
//            if(count($this->registrationIds) == 1)
//            {
//                $json['to'] = $this->registrationIds[0];
//            }
//            else
//            {
//                $json['registration_ids'] = $this->registrationIds;
//            }
            $json['registration_ids'] = $this->registrationIds;
        }

        if ($this->collapseKey) {
            $json['collapse_key'] = $this->collapseKey;
        }

        if (! empty($this->data)) {
            $json['data'] = $this->data;
        }

        if (! empty($this->notification)) {
            $json['notification'] = $this->notification;
        }

        if ($this->delayWhileIdle) {
            $json['delay_while_idle'] = $this->delayWhileIdle;
        }

        if ($this->timeToLive != 2419200) {
            $json['time_to_live'] = $this->timeToLive;
        }

        if ($this->restrictedPackageName) {
            $json['restricted_package_name'] = $this->restrictedPackageName;
        }

        if ($this->dryRun) {
            $json['dry_run'] = $this->dryRun;
        }



        return Json::encode($json);
    }
}
