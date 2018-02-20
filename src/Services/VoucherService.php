<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\Recipient;
use App\Models\SpecialOffer;
use App\Repositories\VoucherRepositoryInterface;
use App\Generators\VoucherCodeGeneratorInterface;
use App\Exceptions\InvalidModelException;
use App\Repositories\RecipientRepositoryInterface;
use App\Exceptions\ModelNotFoundException;
use App\Repositories\SpecialOfferRepositoryInterface;
use App\Utils\ClockInterface;

class VoucherService
{
    private $voucherCodeGenerator;
    private $recipientRepository;
    private $specialOfferRepository;
    private $voucherRepository;
    private $clock;

    public function __construct(
        VoucherCodeGeneratorInterface $voucherCodeGenerator,
        RecipientRepositoryInterface $recipientRepository,
        SpecialOfferRepositoryInterface $specialOfferRepository,
        VoucherRepositoryInterface $voucherRepository,
        ClockInterface $clock
    ) {
        $this->voucherCodeGenerator = $voucherCodeGenerator;
        $this->recipientRepository = $recipientRepository;
        $this->specialOfferRepository = $specialOfferRepository;
        $this->voucherRepository = $voucherRepository;
        $this->clock = $clock;
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

    public function useVoucher(string $voucherCode, string $email)
    {
        $voucher = $this->voucherRepository->getByCodeAndEmail($voucherCode, $email);
        $this->validateVoucher($voucher);
        $specialOffer = $this->specialOfferRepository->getById($voucher->specialOfferId);
        $voucher->usedAt = $this->clock->now();

        $this->voucherRepository->update($voucher);

        return $specialOffer;
    }

    private function validateVoucher($voucher)
    {
        if ($voucher == null) {
            throw new ModelNotFoundException();
        }

        if ($voucher->usedAt != null) {
            throw new InvalidModelException(['Voucher is used already']);
        }
    }

    public function getActiveVouchersByEmail(string $email)
    {
        return $this->voucherRepository->getActiveVouchersByEmail($email);
    }
}
