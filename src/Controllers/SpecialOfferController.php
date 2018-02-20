<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Models\SpecialOffer;
use App\Services\SpecialOfferService;

class SpecialOfferController
{
    private $actionExecuter;
    private $specialOfferService;

    public function __construct(
        SpecialOfferService $specialOfferService,
        ControllerActionExecuter $actionExecuter
    ) {
        $this->specialOfferService = $specialOfferService;
        $this->actionExecuter = $actionExecuter;
        $this->actionExecuter->setController($this);
    }

    public function getAll(Request $request, Response $response)
    {
        return $this->actionExecuter->execute(function () use ($request, $response) {
            $offers = $this->specialOfferService->getAll();
            $responseData = [];
            foreach ($offers as $offer) {
                $responseData[] = $offer->toAssocArray();
            }

            return $response->withJson($responseData, 200);
        }, $response);
    }

    public function getByCode(Request $request, Response $response, string $code)
    {
        return $this->actionExecuter->execute(function () use ($request, $response, $code) {
            $offer = $this->specialOfferService->getByCode($code);
            return $response->withJson($offer->toAssocArray(), 200);
        }, $response);
    }

    public function create(Request $request, Response $response)
    {
        return $this->actionExecuter->execute(function () use ($request, $response) {
            $requestBody = $request->getParsedBody();
            $name = $requestBody['name'];
            $discount = $requestBody['discount'];

            $specialOffer = new SpecialOffer($name, $discount);
            $this->specialOfferService->save($specialOffer);

            return $response->withJson([
                'code' => $specialOffer->code,
                'type' => 'SpecialOffer'
            ], 201);
        }, $response);
    }
}
