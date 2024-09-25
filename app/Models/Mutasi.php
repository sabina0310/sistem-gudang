<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use HasFactory;

    protected $table = 'mutasi';

    protected $fillable = [
        'barang_id',
        'tanggal',
        'jenis_mutasi',
        'jumlah',
        'created_by'
    ];

    public function barang()
    {
        return $this->hasOne(Barang::class, 'id', 'barang_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id')
            ->select('id', 'nama');
    }
}
