<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\ChannelEnum;
use App\Validator as CustomAssert;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
class NotificationModel
{
    #[Assert\Positive]
    #[CustomAssert\ExistingRecipient]
    public int $userId;

    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[OA\Property(example: "Important Notification")]
    public string $subject;

    #[Assert\NotBlank]
    #[OA\Property(example: "This is the content of the notification.")]
    public string $content;

    /**
     * @var ChannelEnum[] $channels
     */
    public array $channels;
}
