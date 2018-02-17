<?php

use Slim\Http\Request;
use Slim\Http\Response;

$ROUTES_PREFIX = "/api/v1.0";

$app->get("/", function (Request $request, Response $response, array $args) {
    return "The application is running";
});

$app->get("$ROUTES_PREFIX/voucher", function (Request $request, Response $response, array $args) {
    return $response->withJson([
        "Name" => "hello"
    ]);
});

$app->get("$ROUTES_PREFIX/sale", function (Request $request, Response $response, array $args) {
    $saleData = $request->getParsedBody();
    $email = $saleData["email"];
    $voucherCode = $saleData["voucher"];
    $voucherService = $this->VoucherService;

    $result = $this->VoucherService->use($voucherCode, $email);
    return $response->withJson($result, 201);
});
