<?php

namespace App\Models;

use App\Traits\CreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MKategori extends Model
{
    use HasFactory,CreatedBy;
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    protected $fillable = ['kode_kategori', 'nama_kategori'];

    function barang() {
        return $this->hasMany(MBarang::class,'id_kategori');
    }
}
