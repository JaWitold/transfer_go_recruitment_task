<?php

declare(strict_types=1);

namespace App\Enum;

enum ChannelEnum: string
{
    case Email = 'email';
    case Chat = 'chat';
    case Sms = 'sms';
}
