<?php

namespace Tests\Services;

use App\Models\Voucher;
use App\Models\Recipient;
use App\Utils\ClockInterface;
use App\Models\SpecialOffer;
use PHPUnit\Framework\TestCase;
use App\Services\VoucherService;
use App\Repositories\VoucherRepositoryInterface;
use App\Generators\VoucherCodeGeneratorInterface;
use App\Repositories\RecipientRepositoryInterface;
use App\Repositories\SpecialOfferRepositoryInterface;

class VoucherServiceTest extends TestCase
{
    private $codeGenerator;
    private $recipientRepository;
    private $specialOfferRepository;
    private $voucherRepository;
    private $voucherService;
    private $specialOffer;
    private $clock;

    protected function setUp()
    {
        $this->codeGenerator = $this->createMock(VoucherCodeGeneratorInterface::class);
        $this->recipientRepository = $this->createMock(RecipientRepositoryInterface::class);
        $this->specialOfferRepository = $this->createMock(SpecialOfferRepositoryInterface::class);
        $this->voucherRepository = $this->createMock(VoucherRepositoryInterface::class);
        $this->clock = $this->createMock(ClockInterface::class);

        $this->specialOffer = new SpecialOffer("Black friday", 35);
        $this->specialOffer->id = 1;

        $this->clock->method('now')->willReturn('2018-02-18 21:00:11');
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
        $this->codeGenerator = $this->createMock(VoucherCodeGeneratorInterface::class);

        $this->voucherService = new VoucherService(
            $this->codeGenerator,
            $this->recipientRepository,
            $this->specialOfferRepository,
            $this->voucherRepository,
            $this->clock
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

    public function test_when_using_a_voucher_and_it_does_not_exist_should_throw_ModelNotFoundException()
    {
        $this->buildVoucherService();
        $this->expectException(\App\Exceptions\ModelNotFoundException::class);
        $this->voucherRepository->method('getByCodeAndEmail')->willReturn(null);

        $this->voucherService->useVoucher("voucherCode", "email@example.com");
    }

    public function test_when_using_a_voucher_and_it_was_already_used_should_throw_InvalidModelException()
    {
        $voucher = new Voucher("voucherCode", 1, 1);
        $voucher->usedAt = date("Y-m-d H:i:s");

        $this->buildVoucherService();
        $this->expectException(\App\Exceptions\InvalidModelException::class);
        $this->voucherRepository->method('getByCodeAndEmail')->willReturn($voucher);

        $this->voucherService->useVoucher("voucherCode", "email@example.com");
    }

    public function test_when_using_a_valid_voucher_should_update_its_used_at_column()
    {
        $voucher = new Voucher("voucherCode", 1, 1);
        $specialOffer = new SpecialOffer("specialOffer", 15);

        $this->buildVoucherService();
        $this->voucherRepository->method('getByCodeAndEmail')->willReturn($voucher);
        $this->specialOfferRepository->method('getById')->willReturn($specialOffer);

        $this->voucherRepository
             ->expects($this->once())
             ->method('update');

        $this->voucherService->useVoucher("voucherCode", "email@example.com");
    }
}