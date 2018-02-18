<?php

namespace App\Repositories;

use App\Utils\Database;
use App\Models\Voucher;

class VoucherRepository implements IVoucherRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getByCode(string $code)
    {
        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare("SELECT voucher WHERE code = :code");
        $query->execute([':code' => $code]);
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
        $voucher->usedAt = $row->usedAt;

        return $voucher;
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
}