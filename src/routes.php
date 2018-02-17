<?php

use Slim\Http\Request;
use Slim\Http\Response;

$ROUTES_PREFIX = "/api/v1.0";

$app->get("/", function (Request $request, Response $response) {
    return "The application is running";
});

$app->post("$ROUTES_PREFIX/voucher", ['App\Controllers\VoucherController', 'generateVouchers']);