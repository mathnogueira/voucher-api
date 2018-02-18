<?php

namespace App\Repositories;

use App\Utils\Database;
use App\Models\SpecialOffer;

class SpecialOfferRepository implements ISpecialOfferRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll()
    {
        $allOffers = [];
        $sqlConnection = $this->database->getConnection();

        $query = $sqlConnection->prepare("SELECT * FROM special_offer");
        $query->execute();
        while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
            $allOffers[] = $this->buildSpecialOfferFromRow($row);
        }

        return $allOffers;
    }

    public function getByCode(string $code)
    {
        $sqlConnection = $this->database->getConnection();

        $query = $sqlConnection->prepare("SELECT * FROM special_offer WHERE code = :code");
        $query->execute([':code' => $code]);

        $row = $query->fetch(\PDO::FETCH_OBJ);
        return $this->buildSpecialOfferFromRow($row);
    }

    private function buildSpecialOfferFromRow($row)
    {
        if (!$row) {
            return null;
        }

        $offer = new SpecialOffer($row->name, $row->discount);
        $offer->id = $row->special_offer_id;
        $offer->code = $row->code;

        return $offer;
    }

    public function save(SpecialOffer $specialOffer)
    {
        $name =$specialOffer->name;
        $code =$specialOffer->code;
        $discount =$specialOffer->discount;

        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare("INSERT INTO special_offer (name, code, discount) VALUES (?, ?, ?)");
        $query->bindParam(1, $name);
        $query->bindParam(2, $code);
        $query->bindParam(3, $discount);

        $query->execute();
        $specialOffer->id = $sqlConnection->lastInsertId();
    }
}