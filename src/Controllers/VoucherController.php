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
        $specialOfferCode = $requestBody['offerCode'];

        $numberVouchersGenerated = $this->voucherService->generateVouchersForSpecialOffer($specialOfferCode);

        return $response->withJson([
            'Created' => $numberVouchersGenerated,
            'type' => 'Voucher'
        ], 201);
    }
}