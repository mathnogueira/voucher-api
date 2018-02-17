<?php

namespace App\Utils;

class Database
{
    public function getConnection()
    {
        return new \PDO("mysql:host=localhost;dbname=voucher_pool", "root", "123");
    }
}