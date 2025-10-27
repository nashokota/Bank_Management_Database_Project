<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'destination_account_id',
        'transaction_type',
        'amount',
        'date_time',
        'balance_after_transaction',
        'description',
        'transfer_reference'
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'amount' => 'decimal:2',
        'balance_after_transaction' => 'decimal:2',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function destinationAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'destination_account_id');
    }
}
