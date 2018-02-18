<?php

namespace App\Services;

use App\Generators\IVoucherCodeGenerator;
use App\Repositories\IRecipientRepository;
use App\Repositories\ISpecialOfferRepository;
use App\Repositories\IVoucherRepository;
use App\Models\Voucher;
use App\Models\Recipient;
use App\Models\SpecialOffer;

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
        $specialOffer = $this->specialOfferRepository->getByCode($specialofferCode);
        $recipients = $this->recipientRepository->getAllRecipientsDoesntHaveVoucherFor($specialOffer);
        
        foreach ($recipients as $index => $recipient) {
            $voucher = $this->createVoucher($recipient, $specialOffer);
            $this->voucherRepository->save($voucher);
        }

        return count($recipients);
        
    }

    private function createVoucher(Recipient $recipient, SpecialOffer $specialOffer)
    {
        $code = $this->generateVoucherCode();
        return new Voucher($code, $recipient->id, $specialOffer->id);
    }

    private function generateVoucherCode()
    {
        do {
            $code = $this->voucherCodeGenerator->generate();
            $voucher = $this->voucherRepository->getByCode($code);
        } while ($voucher != null);

        return $code;
    }
}