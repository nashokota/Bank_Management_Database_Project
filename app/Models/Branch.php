<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = [
        'branch_name',
        'address',
        'phone'
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
