<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPesanan extends Model
{
    use HasFactory;

    protected $table = 'detail_pesanan';

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'product_name',
        'serving_type',
        'item_option',
        'item_note',
        'price',
        'quantity',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
