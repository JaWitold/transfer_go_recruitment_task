<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Notification;
use App\Event\NotificationEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: NotificationEvent::NAME, method: 'onNotificationSent')]
readonly class NotificationSentListener
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function onNotificationSent(NotificationEvent $event): void
    {
        $model = $event->getNotification();
        $notification = (new Notification())
            ->setSubject($model->subject)
            ->setContent($model->content)
            ->setUserId($model->userId)
            ->setChannels($model->channels);
        $this->em->persist($notification);
        $this->em->flush();
    }
}
