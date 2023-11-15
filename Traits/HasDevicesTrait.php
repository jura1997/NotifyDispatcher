<?php

namespace YuraDev\NotifyDispatcher\Traits;

use YuraDev\NotifyDispatcher\Models\Device;

trait HasDevicesTrait
{
    public function devices()
    {
        return $this->morphMany(Device::class, 'deviceable');
    }

    public function addDevice($device)
    {
        $this->devices()->create($device);
    }

    public function removeDevice($device)
    {
        $this->devices()->where('id', $device['id'])->delete();
    }

    public function removeAllDevices()
    {
        $this->devices()->delete();
    }

    public function getDevice($device)
    {
        return $this->devices()->where('id', $device['id'])->first();
    }

    public function getDeviceByToken($token)
    {
        return $this->devices()->where('token', $token)->first();
    }

    public function getDevices()
    {
        return $this->devices()->get();
    }

}
