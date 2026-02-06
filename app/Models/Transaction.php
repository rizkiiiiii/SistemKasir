<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Add SoftDeletes
use App\Traits\AuditLogger; // Add AuditLogger

class Transaction extends Model
{
    use HasFactory, SoftDeletes, AuditLogger;

    protected $guarded = ['id'];

    protected $casts = [
        'transaction_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'cash_paid' => 'decimal:2',
        'change_returned' => 'decimal:2',
    ];

    // Relasi ke User (Kasir)
    public function cashier()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Detail Transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed'); // Atau sesuaikan dengan status yang ada
    }
}
