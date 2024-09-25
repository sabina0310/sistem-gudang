<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'nama_barang',
        'kode',
        'kategori',
        'lokasi',
        'jumlah_stok',
        'created_by',
        'updated_by'
    ];

    public function mutasi(): HasMany
    {
        return $this->hasMany(Mutasi::class, 'barang_id', 'id');
    }

    // public function createdBy()
    // {
    //     return $this->belongsTo(User::class, 'created_by', 'id')
    //         ->select('id', 'nama');
    // }
}
