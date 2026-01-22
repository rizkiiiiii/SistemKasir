<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi ke User (Kasir)
    public function cashier()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Detail Transaksi
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
