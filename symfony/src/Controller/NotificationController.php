<?php

declare(strict_types=1);

namespace App\Controller;

use App\Event\NotificationEvent;
use App\Model\NotificationModel;
use App\Service\UserProvider\UserProviderInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

#[Route('/api/notification', name: 'api_notification_')]
class NotificationController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }


    #[Route('', name: 'index', methods: [Request::METHOD_POST])]
    #[OA\Post(
        path: '/api/notification',
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(type: NotificationModel::class)
            ),
        ),
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: "OK",
                content: new OA\JsonContent(
                    example: "OK"
                )
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: "Validation Failed",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "type",
                            type: "string",
                            example: "https://symfony.com/doc/current/validation.html"
                        ),
                        new OA\Property(property: "title", type: "string", example: "Validation Failed"),
                        new OA\Property(
                            property: "status",
                            type: "integer",
                            example: Response::HTTP_UNPROCESSABLE_ENTITY
                        ),
                        new OA\Property(
                            property: "detail",
                            type: "string",
                            example: "This value should be of type int."
                        ),
                    ],
                    type: "object"
                )
            ),
            new OA\Response(
                response: Response::HTTP_BAD_REQUEST,
                description: "Bad Request",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: "type",
                            type: "string",
                            example: "https://symfony.com/doc/current/validation.html"
                        ),
                        new OA\Property(property: "title", type: "string", example: "Validation Failed"),
                        new OA\Property(property: "status", type: "integer", example: Response::HTTP_BAD_REQUEST),
                        new OA\Property(
                            property: "detail",
                            type: "string",
                            example: "Request payload contains invalid \"json\" data."
                        ),
                    ],
                    type: "object"
                )
            )
        ]
    )]
    public function index(
        #[MapRequestPayload] NotificationModel $model,
        NotifierInterface $notifier,
        UserProviderInterface $userProvider
    ): Response {
        $notification = (new Notification($model->subject, array_map(fn($ch) => $ch->value, $model->channels)))
            ->content($model->content);

        // The receiver of the Notification
        /** @var RecipientInterface $recipient */
        $recipient = $userProvider->getUser($model->userId);

        // Send the notification to the recipient
        $notifier->send($notification, $recipient);

        // Dispatch the event
        $event = new NotificationEvent($model);
        $this->eventDispatcher->dispatch($event, NotificationEvent::NAME);
        return new JsonResponse("OK", Response::HTTP_OK);
    }
}
