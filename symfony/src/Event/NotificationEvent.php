<?php

declare(strict_types=1);

namespace App\Event;

use App\Model\NotificationModel;
use Symfony\Contracts\EventDispatcher\Event;

class NotificationEvent extends Event
{
    public const string NAME = "NotificationEvent";

    public function __construct(private readonly NotificationModel $notification)
    {
    }

    public function getNotification(): NotificationModel
    {
        return $this->notification;
    }
}
