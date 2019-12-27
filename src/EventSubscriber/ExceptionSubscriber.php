<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;


class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();        

        if($exception instanceof NotFoundHttpException) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => 'Resource not found'
            ];


        $response = new JsonResponse($data);
        $event->setResponse($response);

    }

}

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}
