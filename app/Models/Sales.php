<?php

namespace App\Models;

use App\Traits\CreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory,CreatedBy;
    protected $table = 'sales';
    protected $primaryKey = 'id_sales';
    protected $fillable = ['id_barang', 'jumlah_sales', 'tanggal_sales'];
    function barang(){
        return $this->hasOne(MBarang::class,'id_barang','id_barang');
    }
    
}
