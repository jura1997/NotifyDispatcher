<?php

namespace YuraDev\NotifyDispatcher\Notification\Data;

abstract class Data
{
    public static function getInstance()
    {
        return new static();
    }
}