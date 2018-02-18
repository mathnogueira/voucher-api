<?php

use Slim\Http\Request;
use Slim\Http\Response;

$ROUTES_PREFIX = "/api/v1.0";

$app->get("/", function (Request $request, Response $response) {
    return "The application is running";
});


$app->get("$ROUTES_PREFIX/recipient", ['App\Controllers\RecipientController', 'getAll']);
$app->get("$ROUTES_PREFIX/recipient/{id}", ['App\Controllers\RecipientController', 'getById']);
$app->post("$ROUTES_PREFIX/recipient", ['App\Controllers\RecipientController', 'create']);

$app->get("$ROUTES_PREFIX/offer", ['App\Controllers\SpecialOfferController', 'getAll']);
$app->get("$ROUTES_PREFIX/offer/{code}", ['App\Controllers\SpecialOfferController', 'getByCode']);
$app->post("$ROUTES_PREFIX/offer", ['App\Controllers\SpecialOfferController', 'create']);

$app->post("$ROUTES_PREFIX/voucher", ['App\Controllers\VoucherController', 'generateVouchers']);