<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detail_transaksi extends Model
{
    protected $table = "detail_transaksi";
    protected $primaryKey = "id";
    protected $fillable = ['id_trans', 'id_jenis', 'qty', 'subtotal'];
    public $timestamps = false;

    public function Transaksi() {
        return $this->belongsTo('App\Transaksi', 'id_trans');
    }
    public function Jenis_cuci() {
        return $this->belongsTo('App\Jenis_cuci', 'id_jenis_cuci');
    }
}
