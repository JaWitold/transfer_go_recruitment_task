<?php

declare(strict_types=1);

namespace App\Service\UserProvider;

use Symfony\Component\Notifier\Recipient\RecipientInterface;

interface UserProviderInterface
{
    public function getUser(int $userId): ?RecipientInterface;
}
