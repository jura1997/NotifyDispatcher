<?php

namespace YuraDev\NotifyDispatcher\Traits;

use YuraDev\NotifyDispatcher\Interfaces\IMultipleDevices;
use YuraDev\NotifyDispatcher\Interfaces\ISingleDevice;

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
        $tokens = [];

        $model = $this;
        if($model instanceof ISingleDevice) {
            $tokens[] = $this->getDeviceToken();
        }
        if ($model instanceof IMultipleDevices) {
            $tokens = array_merge($tokens, $this->getDevicesTokens());
        }

        return array_unique($tokens);;
    }

    public function checkDataForGcm()
    {
        return !empty($this->routeNotificationForGcm());
    }
}
