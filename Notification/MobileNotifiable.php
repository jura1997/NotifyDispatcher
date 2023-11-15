<?php

namespace YuraDev\NotifyDispatcher\Notification;

interface MobileNotifiable
{
    public function setChannels();
    public function getChannels();
    public function checkDataForGcm();
}