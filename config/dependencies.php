<?php

use function DI\object;

return [
    // Generators
    'App\Generators\IVoucherCodeGenerator' => object(App\Generators\VoucherCodeGenerator::class),

    // Services
    'App\Services\VoucherService' => object(App\Services\VoucherService::class),

    // Repositories
    'App\Repositories\IRecipientRepository' => object(App\Repositories\RecipientRepository::class),
    'App\Repositories\ISpecialOfferRepository' => object(App\Repositories\SpecialOfferRepository::class),
    'App\Repositories\IVoucherRepository' => object(App\Repositories\VoucherRepository::class),
    
    //Controllers
    'App\Controllers\VoucherController' => object(App\Controllers\VoucherController::class)
];