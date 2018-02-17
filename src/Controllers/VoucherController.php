<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\VoucherService;

class VoucherController
{
    private $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    public function generateVouchers(Request $request, Response $response)
    {
        $requestBody = $request->getParsedBody();
        $specialOfferCode = $requestBody['specialOfferCode'];

        $this->voucherService->generateVouchersForSpecialOffer($specialOfferCode);

        return $response->withJson([], 201);
    }
}