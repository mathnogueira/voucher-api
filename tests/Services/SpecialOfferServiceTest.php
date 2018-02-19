<?php

namespace Tests\Services;

use App\Models\SpecialOffer;
use PHPUnit\Framework\TestCase;
use App\Services\SpecialOfferService;
use App\Models\Validation\ModelValidator;
use App\Exceptions\InvalidModelException;
use App\Exceptions\ModelNotFoundException;
use App\Repositories\SpecialOfferRepositoryInterface;
use App\Generators\SpecialOfferCodeGeneratorInterface;

class SpecialOfferServiceTest extends TestCase
{
    private $modelValidator;
    private $specialOfferRepository;
    private $codeGenerator;
    private $specialOfferService;

    protected function setUp()
    {
        $this->modelValidator = $this->createMock(ModelValidator::class);
        $this->specialOfferRepository = $this->createMock(SpecialOfferRepositoryInterface::class);
        $this->codeGenerator = $this->createMock(SpecialOfferCodeGeneratorInterface::class);
        $this->specialOfferService = new SpecialOfferService(
            $this->specialOfferRepository,
            $this->codeGenerator,
            $this->modelValidator
        );
    }

    public function test_when_getting_offer_by_code_and_it_doesnt_exist_should_throw_ModelNotFoundException()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->specialOfferRepository->method('getByCode')->willReturn(null);

        $this->specialOfferService->getByCode("abdE");
    }

    public function test_when_getting_offer_by_code_and_it_exists_should_return_the_special_offer()
    {
        $expectedOffer = new SpecialOffer("Offer name", 5);
        $this->specialOfferRepository->method('getByCode')->willReturn($expectedOffer);

        $offer = $this->specialOfferService->getByCode("abcG");
        $this->assertEquals($expectedOffer, $offer);
    }

    public function test_when_saving_an_invalid_offer_should_throw_InvalidModelException()
    {
        $this->expectException(InvalidModelException::class);
        $invalidOffer = new SpecialOffer("", 12.5);
        $this->modelValidator->method('validate')->willReturn(['Discount must be numeric']);

        $this->specialOfferService->save($invalidOffer);
    }

    public function test_when_saving_a_special_offer_should_guarantee_its_code_is_unique()
    {
        $this->modelValidator->method('validate')->willReturn([]);
        $this->configureRepositoryToDenyCodeTwiceAndThenAllowItsUse();
        
        // Code generation should be executed three times
        $this->codeGenerator
            ->expects($this->exactly(3))
            ->method('generate')
            ->willReturn('Ax7l');

        // Should try to save just once
        $this->specialOfferRepository
            ->expects($this->once())
            ->method('save');
        
        $specialOffer = new SpecialOffer("Black friday", 40);
        $this->specialOfferService->save($specialOffer);

    }

    private function configureRepositoryToDenyCodeTwiceAndThenAllowItsUse()
    {
        $alreadyExistingOffer = new SpecialOffer("Already existing", 13);
        // Code already exists in database
        $this->specialOfferRepository
            ->expects($this->at(0))
            ->method('getByCode')
            ->willReturn($alreadyExistingOffer);
        
        // Code already exists in database (again)
        $this->specialOfferRepository
            ->expects($this->at(1))
            ->method('getByCode')
            ->willReturn($alreadyExistingOffer);
        
        // Code is not in database yet. It is good to go
        $this->specialOfferRepository
            ->expects($this->at(2))
            ->method('getByCode')
            ->willReturn(null);
    }
}
