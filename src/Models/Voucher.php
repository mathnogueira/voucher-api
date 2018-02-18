<?php

namespace App\Models;

class Voucher extends Model
{
    protected $fields = ['id', 'code', 'recipientId', 'specialOfferId', 'usedAt'];
    protected $readonlyFields = ['code', 'recipientId', 'specialOfferId'];
    protected $validations = [
        'recipientId' => [
            ['not empty' => 'The voucher must be associated with a recipient'],
        ],
        'specialOfferId' => [
            ['not empty' => 'The voucher must be associated with a special offer'],
        ]
    ];
    
    public function __construct(string $code, int $recipientId, int $specialOfferId)
    {
        $this->code = $code;
        $this->recipientId = $recipientId;
        $this->specialOfferId = $specialOfferId;
        $this->usedAt = null;
    }
}