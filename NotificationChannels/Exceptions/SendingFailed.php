<?php

namespace YuraDev\NotifyDispatcher\NotificationChannels\Exceptions;

use Exception;

class SendingFailed extends Exception
{
    /**
     * @param \Exception $exception
     * @return SendingFailed
     */
    public static function create(Exception $exception)
    {
        return new static("Cannot send message to Gcm: {$exception->getMessage()}", 0, $exception);
    }
}
