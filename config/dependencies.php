<?php

use function DI\object;

return [
    // Generators
    'App\Generators\VoucherCodeGeneratorInterface' => object(App\Generators\VoucherCodeGenerator::class),
    'App\Generators\SpecialOfferCodeGeneratorInterface' => object(App\Generators\SpecialOfferCodeGenerator::class),

    // Services
    'App\Services\VoucherService' => object(App\Services\VoucherService::class),

    // Repositories
    'App\Repositories\RecipientRepositoryInterface' => object(App\Repositories\RecipientRepository::class),
    'App\Repositories\SpecialOfferRepositoryInterface' => object(App\Repositories\SpecialOfferRepository::class),
    'App\Repositories\VoucherRepositoryInterface' => object(App\Repositories\VoucherRepository::class),
    
    //Controllers
    'App\Controllers\VoucherController' => object(App\Controllers\VoucherController::class),

    // Utils
    'App\Utils\ClockInterface' => object(App\Utils\SystemClock::class)
];
