<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\RecipientService;
use App\Models\Recipient;

class RecipientController
{
    private $recipientService;
    private $actionExecuter;

    public function __construct(
        RecipientService $recipientService,
        ControllerActionExecuter $controllerActionExecuter
    ) {
        $this->recipientService = $recipientService;
        $this->actionExecuter = $controllerActionExecuter;
        $this->actionExecuter->setController($this);
    }

    public function getAll(Request $request, Response $response)
    {
        return $this->actionExecuter->execute(function () use ($request, $response) {
            $recipients = $this->recipientService->getAll();
            $responseData = [];
            foreach ($recipients as $recipient) {
                $responseData[] = $recipient->toAssocArray();
            }

            return $response->withJson($responseData, 200);
        }, $response);
    }

    public function getById(Request $request, Response $response, int $id)
    {
        return $this->actionExecuter->execute(function () use ($request, $response, $id) {
            $recipient = $this->recipientService->getById($id);
            return $response->withJson($recipient->toAssocArray(), 200);
        }, $response);
    }

    public function create(Request $request, Response $response)
    {
        return $this->actionExecuter->execute(function () use ($request, $response) {
            $requestBody = $request->getParsedBody();
            $name = $requestBody['name'];
            $email = $requestBody['email'];

            $recipient = new Recipient($name, $email);
            $this->recipientService->save($recipient);

            return $response->withJson([
                    'id' => $recipient->id,
                    'type' => 'Recipient'
            ], 201);
        }, $response);
    }
}