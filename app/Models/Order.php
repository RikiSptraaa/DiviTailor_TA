<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'is_acc', 
        'order_date', 
        'tanggal_estimasi', 
        'jenis_pakaian', 
        'jenis_pembuatan',
        'jenis_kain',
        'jenis_panjang',
        'deskripsi_pakaian',
        'total_harga', 
        'invoice_number', 
        'user_id'];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function task()
    {
        return $this->hasOne(Task::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    protected $with = ['user'];
}
