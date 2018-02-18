<?php

namespace Tests\Services;

use App\Models\Voucher;
use App\Models\Recipient;
use App\Models\SpecialOffer;
use PHPUnit\Framework\TestCase;
use App\Services\VoucherService;
use App\Repositories\IVoucherRepository;
use App\Generators\IVoucherCodeGenerator;
use App\Repositories\IRecipientRepository;
use App\Repositories\ISpecialOfferRepository;

class VoucherServiceTest extends TestCase
{
    private $codeGenerator;
    private $recipientRepository;
    private $specialOfferRepository;
    private $voucherRepository;
    private $voucherService;
    private $specialOffer;

    protected function setUp()
    {
        $this->codeGenerator = $this->createMock(IVoucherCodeGenerator::class);
        $this->recipientRepository = $this->createMock(IRecipientRepository::class);
        $this->specialOfferRepository = $this->createMock(ISpecialOfferRepository::class);
        $this->voucherRepository = $this->createMock(IVoucherRepository::class);

        $this->specialOffer = new SpecialOffer("Black friday", 35);
        $this->specialOffer->id = 1;
    }

    public function test_when_generating_vouchers_that_were_generated_before_should_generate_zero_vouchers()
    {
        $this->buildVoucherService();
        $this->specialOfferRepository->method('getByCode')->willReturn($this->specialOffer);
        $this->recipientRepository->method('getAllRecipientsDoesntHaveVoucherFor')->willReturn([]);

        $this->voucherRepository
            ->expects($this->exactly(0))
            ->method('save');

        $this->voucherService->generateVouchersForSpecialOffer("ofcd");
    }

    public function test_when_generating_vouchers_should_generate_only_for_recipients_that_dont_own_one()
    {
        $this->buildVoucherService();
        $recipients = $this->getRecipients();
        $this->specialOfferRepository->method('getByCode')->willReturn($this->specialOffer);
        $this->recipientRepository->method('getAllRecipientsDoesntHaveVoucherFor')->willReturn($recipients);

        $this->codeGenerator
             ->expects($this->exactly(2))
             ->method('generate')
             ->willReturn('AbCdEfGhIj');
             
        $this->voucherRepository->method('getByCode')->willReturn(null);

        $numberVounchers = count($recipients);
        $this->voucherRepository
            ->expects($this->exactly($numberVounchers))
            ->method('save');

        $this->assertEquals($numberVounchers, $this->voucherService->generateVouchersForSpecialOffer("abc"));
    }

    private function getRecipients()
    {
        $recipients = [
            new Recipient("John Doe", "john.doe@gmail.com"),
            new Recipient("Kate Doe", "doe.kate@outlook.com")
        ];
        foreach ($recipients as $index => $recipient) {
            $recipient->id = $index+1;
        }

        return $recipients;
    }

    public function test_when_generating_the_voucher_code_should_guarantee_it_is_unique()
    {
        $this->buildVoucherService();
        $recipients = $this->getRecipients();
        $this->specialOfferRepository->method('getByCode')->willReturn($this->specialOffer);
        $this->recipientRepository->method('getAllRecipientsDoesntHaveVoucherFor')->willReturn($recipients);
        $this->configureRepositoryToDenyFirstTwoCodesAndAllowTheThird();

        // Should generate the code four times
        // Three times for the voucher for the first recipient
        // One time for the voucher for the second recipient
        $this->codeGenerator
             ->expects($this->exactly(4))
             ->method('generate')
             ->willReturn('AbCdEfGhIj');

        $this->voucherService->generateVouchersForSpecialOffer("abc");
    }

    private function buildVoucherService()
    {
        $this->codeGenerator = $this->createMock(IVoucherCodeGenerator::class);

        $this->voucherService = new VoucherService(
            $this->codeGenerator,
            $this->recipientRepository,
            $this->specialOfferRepository,
            $this->voucherRepository
        );
    }

    private function configureRepositoryToDenyFirstTwoCodesAndAllowTheThird()
    {
        $existingVoucher = new Voucher("a", 2, 3);

        $this->voucherRepository
             ->expects($this->at(0))
             ->method('getByCode')
             ->willReturn($existingVoucher);
        
        $this->voucherRepository
             ->expects($this->at(1))
             ->method('getByCode')
             ->willReturn($existingVoucher);

        $this->voucherRepository
             ->expects($this->at(2))
             ->method('getByCode')
             ->willReturn(null);
    }
}