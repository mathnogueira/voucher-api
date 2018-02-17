<?php

namespace App\Repositories;

use App\Models\Recipient;
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