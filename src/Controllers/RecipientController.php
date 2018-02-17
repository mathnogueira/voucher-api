<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\RecipientService;
use App\Models\Recipient;

class RecipientController
{
    private $recipientService;

    public function __construct(RecipientService $recipientService)
    {
        $this->recipientService = $recipientService;
    }

    public function create(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();
        $name = $requestBody['name'];
        $email = $requestBody['email'];

        $recipient = new Recipient($name, $email);
        $this->recipientService->
    }
}