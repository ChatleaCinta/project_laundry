<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = "pelanggan";
    protected $primaryKey = "id";
    protected $fillable = ['nama', 'alamat', 'telp'];
    public $timestamps = false;

    public function Pelanggan(){
    return $this->HasMany('App/Pelanggan','id');
}
}