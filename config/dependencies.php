<?php
// DIC configuration

class Teste {
    public function do() {
        return "Done";
    }
}

$container = $app->getContainer();

$container["VoucherService"] = function($container) {
    $voucherService = new Teste();
    return $voucherService;
};
