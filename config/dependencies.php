<?php

use function DI\object;

return [
    // Generators
    'App\Generators\IVoucherCodeGenerator' => object(App\Generators\VoucherCodeGenerator::class),

    // Services
    'App\Services\VoucherService' => object(App\Services\VoucherService::class),

    // Repositories
    'App\Repositories\IClientRepository' => object(App\Repositories\ClientRepository::class),
    'App\Repositories\ISpecialOfferRepository' => object(App\Repositories\SpecialOfferRepository::class),
    
    //Controllers
    'App\Controllers\VoucherController' => object(App\Controllers\VoucherController::class)
];