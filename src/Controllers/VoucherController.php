<?php

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Services\VoucherService;

class VoucherController
{
    private $voucherService;
    private $actionExecuter;

    public function __construct(VoucherService $voucherService, ControllerActionExecuter $actionExecuter)
    {
        $this->voucherService = $voucherService;
        $this->actionExecuter = $actionExecuter;
        $this->actionExecuter->setController($this);
    }

    public function generateVouchers(Request $request, Response $response)
    {
        return $this->actionExecuter->execute(function () use ($request, $response) {
            $requestBody = $request->getParsedBody();
            $specialOfferCode = $requestBody['offerCode'];

            $numberGeneratedVouchers = $this->voucherService->generateVouchersForSpecialOffer($specialOfferCode);

            return $response->withJson([
                'created' => $numberGeneratedVouchers,
                'type' => 'Voucher'
            ], 201);
        }, $response);
    }

    public function useVoucher(Request $request, Response $response)
    {
        return $this->actionExecuter->execute(function () use ($request, $response) {
            $requestBody = $request->getParsedBody();
            $email = $requestBody['email'];
            $voucherCode = $requestBody['voucherCode'];

            $specialOffer = $this->voucherService->useVoucher($voucherCode, $email);

            return $response->withJson([
                'Discount' => $specialOffer->discount
            ], 200);
        }, $response);
    }

    public function getActiveVouchersForRecipient(Request $request, Response $response)
    {
        return $this->actionExecuter->execute(function () use ($request, $response) {
            $requestBody = $request->getParsedBody();
            $email = $requestBody['email'];

            $activeVouchers = $this->voucherService->getActiveVouchersByEmail($email);

            return $response->withJson($activeVouchers, 200);
        }, $response);
    }
}