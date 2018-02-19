<?php

namespace App\Services;

use App\Models\SpecialOffer;
use App\Models\Validation\ModelValidator;
use App\Exceptions\InvalidModelException;
use App\Exceptions\ModelNotFoundException;
use App\Repositories\SpecialOfferRepositoryInterface;
use App\Generators\SpecialOfferCodeGeneratorInterface;

class SpecialOfferService
{
    private $specialOfferRepository;
    private $codeGenerator;
    private $modelValidator;

    public function __construct(
        SpecialOfferRepositoryInterface $specialOfferRepository,
        SpecialOfferCodeGeneratorInterface $specialOfferCodeGenerator,
        ModelValidator $modelValidator
    ) {
        $this->specialOfferRepository = $specialOfferRepository;
        $this->codeGenerator = $specialOfferCodeGenerator;
        $this->modelValidator = $modelValidator;
    }

    public function getAll()
    {
        return $this->specialOfferRepository->getAll();
    }

    public function getByCode(string $code)
    {
        $offer = $this->specialOfferRepository->getByCode($code);
        if ($offer == null) {
            throw new ModelNotFoundException();
        }

        return $offer;
    }

    public function save(SpecialOffer $specialOffer)
    {
        $errors = $this->modelValidator->validate($specialOffer);
        if (count($errors) > 0) {
            throw new InvalidModelException($errors);
        }

        $code = $this->generateUniqueCode();
        $specialOffer->code = $code;
        $this->specialOfferRepository->save($specialOffer);
    }

    private function generateUniqueCode()
    {
        do {
            $code = $this->codeGenerator->generate();
            $exisingSpecialOffer = $this->specialOfferRepository->getByCode($code);
        } while ($exisingSpecialOffer != null);

        return $code;
    }
}