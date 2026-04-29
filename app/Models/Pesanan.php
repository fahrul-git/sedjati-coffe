<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'table_number',
        'order_date',
        'status',
        'payment_method',
        'payment_status',
        'paid_amount',
        'paid_at',
        'total_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'order_date' => 'datetime',
            'paid_at' => 'datetime',
            'paid_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPesanan(): HasMany
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id');
    }

    public function getChangeAmountAttribute(): float
    {
        $paidAmount = (float) ($this->paid_amount ?? 0);
        $totalAmount = (float) ($this->total_amount ?? 0);

        return max($paidAmount - $totalAmount, 0);
    }
}
