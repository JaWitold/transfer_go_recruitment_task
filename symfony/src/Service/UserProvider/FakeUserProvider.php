<?php

declare(strict_types=1);

namespace App\Service\UserProvider;

use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

readonly class FakeUserProvider implements UserProviderInterface
{
    /**
     * @param Recipient[] $recipients
     */
    public function __construct(
        private array $recipients = [
            new Recipient('user1@gmail.com', '111111111'),
            new Recipient('user2@gmail.com', '222222222'),
            new Recipient('user3@gmail.com', '333333333')
        ]
    ) {
    }

    /** TODO: implement real service */
    public function getUser(int $userId): ?RecipientInterface
    {
        return $this->recipients[$userId] ?? null;
    }
}
