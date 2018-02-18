<?php

namespace App\Repositories;

use App\Models\Recipient;
use App\Models\SpecialOffer;
use App\Utils\Database;

class RecipientRepository implements IRecipientRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll()
    {
        $allRecipients = [];
        $sqlConnection = $this->database->getConnection();

        $query = $sqlConnection->prepare("SELECT * FROM recipient");
        $query->execute();
        while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
            $allRecipients[] = $this->buildRecipientFromRow($row);
        }

        return $allRecipients;
    }

    public function getByEmail(string $email)
    {
        $sqlConnection = $this->database->getConnection();

        $query = $sqlConnection->prepare("SELECT * FROM recipient WHERE email = :email");
        $query->execute([':email' => $email]);

        $row = $query->fetch(\PDO::FETCH_OBJ);
        return $this->buildRecipientFromRow($row);
    }

    public function getById(int $id)
    {
        $sqlConnection = $this->database->getConnection();

        $query = $sqlConnection->prepare("SELECT * FROM recipient WHERE recipient_id = :id");
        $query->execute([':id' => $id]);

        $row = $query->fetch(\PDO::FETCH_OBJ);
        return $this->buildRecipientFromRow($row);
    }

    public function getAllRecipientsDoesntHaveVoucherFor(SpecialOffer $specialOffer)
    {
        $sqlStatement = "
            SELECT * FROM recipient WHERE recipient_id NOT IN (
                SELECT recipient_id FROM `voucher` WHERE special_offer_id = :offerId
            )";
        
        $recipients = [];
        $specialOfferId = $specialOffer->id;
        $sqlConnection = $this->database->getConnection();
        $query = $sqlConnection->prepare($sqlStatement);
        $query->execute([':offerId' => $specialOfferId]);

        while ($row = $query->fetch(\PDO::FETCH_OBJ)) {
            $recipients[] = $this->buildRecipientFromRow($row);
        }

        return $recipients;
    }

    private function buildRecipientFromRow($row)
    {
        if (!$row) {
            return null;
        }
        $recipient = new Recipient($row->name, $row->email);
        $recipient->id = $row->recipient_id;

        return $recipient;
    }

    public function save(Recipient $recipient)
    {
        $name = $recipient->name;
        $email = $recipient->email;
        $sqlConnection = $this->database->getConnection();

        $query = $sqlConnection->prepare("INSERT INTO recipient (name, email) VALUES (?, ?)");
        $query->bindParam(1, $name);
        $query->bindParam(2, $email);
        $query->execute();

        $recipient->id = $sqlConnection->lastInsertId();
    }
}