<?php

namespace App\Repositories;

use App\Utils\Database;
use App\Models\Voucher;

class VoucherRepository implements VoucherRepositoryInterface
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getByCode(string $code)
    {
        $sqlStatement = "
            SELECT * FROM voucher 
            INNER JOIN recipient ON voucher.recipient_id = recipient.recipient_id
            WHERE voucher.code = :code
        ";
        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare($sqlStatement);
        $query->execute([':code' => $code]);
        $row = $query->fetch(\PDO::FETCH_OBJ);

        return $this->buildVoucherFromRow($row);
    }


    public function getByCodeAndEmail(string $code, string $email)
    {
        $sqlStatement = "
            SELECT * FROM voucher 
            INNER JOIN recipient ON voucher.recipient_id = recipient.recipient_id
            WHERE voucher.code = :code AND recipient.email = :email
        ";
        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare($sqlStatement);
        $query->execute([':code' => $code, ':email' => $email]);
        $row = $query->fetch(\PDO::FETCH_OBJ);

        return $this->buildVoucherFromRow($row);
    }

    private function buildVoucherFromRow($row)
    {
        if (!$row) {
            return null;
        }

        $voucher = new Voucher($row->code, $row->recipient_id, $row->special_offer_id);
        $voucher->id = $row->voucher_id;
        $voucher->usedAt = $row->used_at;

        return $voucher;
    }

    public function getActiveVouchersByEmail(string $email)
    {
        $sqlStatement = "
            SELECT voucher.code, special_offer.name FROM voucher 
            INNER JOIN recipient ON voucher.recipient_id = recipient.recipient_id
            INNER JOIN special_offer ON voucher.special_offer_id = special_offer.special_offer_id
            WHERE recipient.email = :email AND voucher.used_at IS NULL
        ";
        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare($sqlStatement);
        $query->execute([':email' => $email]);

        // Here I didn't think it would have any advantage in creating another model
        // class.
        $rows = [];
        while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
            $rows[] = [
                'voucherCode' => $row->code,
                'specialOfferName' => $row->name
            ];
        }

        return $rows;
    }

    public function save(Voucher $voucher)
    {
        $code = $voucher->code;
        $recipientId = $voucher->recipientId;
        $specialOfferId = $voucher->specialOfferId;

        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare("INSERT INTO voucher (code, recipient_id, special_offer_id) VALUES (?, ?, ?)");
        $query->bindParam(1, $code);
        $query->bindParam(2, $recipientId);
        $query->bindParam(3, $specialOfferId);

        $query->execute();
    }

    public function update(Voucher $voucher)
    {
        $id = $voucher->id;
        $usedAt = $voucher->usedAt;

        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare("UPDATE voucher SET used_at = ? WHERE voucher_id = ?");
        $query->bindParam(1, $usedAt);
        $query->bindParam(2, $id);

        $query->execute();
    }
}