<?php

namespace YuraDev\NotifyDispatcher\Notification;

trait MobileNotification
{
    private $via = [];

    public function setChannels(...$params)
    {
        $this->via = $params;

        return $this;
    }

    public function getChannels()
    {
        return $this->via;
    }

    public function routeNotificationForGcm()
    {
        return $this->device_token;
    }

    public function checkDataForGcm()
    {
        return !empty($this->device_token);
    }
}
