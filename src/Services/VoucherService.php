<?php

namespace App\Services;

use App\Generators\IVoucherCodeGenerator;
use App\Repositories\IRecipientRepository;
use App\Repositories\ISpecialOfferRepository;
use App\Repositories\IVoucherRepository;

class VoucherService
{
    private $voucherCodeGenerator;
    private $recipientRepository;
    private $specialOfferRepository;
    private $voucherRepository;

    public function __construct(
        IVoucherCodeGenerator $voucherCodeGenerator,
        IRecipientRepository $recipientRepository,
        ISpecialOfferRepository $specialOfferRepository,
        IVoucherRepository $voucherRepository
    ) {
        $this->voucherCodeGenerator = $voucherCodeGenerator;
        $this->recipientRepository = $recipientRepository;
        $this->specialOfferRepository = $specialOfferRepository;
        $this->voucherRepository = $voucherRepository;
    }

    public function generateVouchersForSpecialOffer(string $specialofferCode)
    {
        $allClients = $this->recipientRepository->getAll();
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