<?php

namespace App\Services;

use App\Generators\IVoucherCodeGenerator;
use App\Repositories\IClientRepository;
use App\Repositories\ISpecialOfferRepository;

class VoucherService
{
    private $voucherCodeGenerator;
    private $clientRepository;
    private $specialOfferRepository;

    public function __construct(
        IVoucherCodeGenerator $voucherCodeGenerator,
        IClientRepository $clientRepository,
        ISpecialOfferRepository $specialOfferRepository
    ) {
        $this->voucherCodeGenerator = $voucherCodeGenerator;
        $this->clientRepository = $clientRepository;
        $this->specialOfferRepository = $specialOfferRepository;
    }

    public function generateVouchersForSpecialOffer(string $specialofferCode)
    {
        $allClients = $this->clientRepository->getAll();
        $specialOffer = $this->specialOfferRepository->getByCode($specialofferCode);

        $voucherList = [];
        foreach ($allClients as $client) {
            $voucherList[] = $this->createVoucher($client, $specialOffer);
        }
    }

    private function createVoucher($client, $specialOffer)
    {
        $code = $this->voucherCodeGenerator->generate();
        return new Voucher($code, $client, $specialOffer);
    }
}