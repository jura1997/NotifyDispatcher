<?php

namespace YuraDev\NotifyDispatcher\Notification;

use App\Models\Interfaces\ISingleDevice;
use App\Models\Interfaces\IMultipleDevices;
use Illuminate\Notifications\Notifiable;

class DeviceCollection implements MobileNotifiable
{
    use Notifiable, MobileNotification;

    private $models = [];

    public function __construct(array  $models)
    {
        $this->models = $models;
    }

    public function routeNotificationForGcm()
    {
        $tokens = [];
        foreach ($this->models as $model) {
           if($model instanceof ISingleDevice) {
               $tokens[] = $model->getDeviceToken();
           }
           if ($model instanceof IMultipleDevices) {
               $tokens = array_merge($tokens, $model->getDevicesTokens());
           }
           $tokens = array_unique($tokens);
        }
        return $tokens;
    }

    public function checkDataForGcm()
    {
        return !empty($this->routeNotificationForGcm());
    }
}
