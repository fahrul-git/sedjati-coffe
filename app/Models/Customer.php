<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'first_purchase_date',
        'total_transactions',
        'total_spending',
    ];

    protected function casts(): array
    {
        return [
            'first_purchase_date' => 'date',
            'total_transactions' => 'integer',
            'total_spending' => 'decimal:2',
        ];
    }
}
