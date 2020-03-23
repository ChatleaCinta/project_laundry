<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detail_transaksi;
use App\Jenis_cuci;
use App\Transaksi;
use JWTAuth;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class DetailTransaksiController extends Controller
{
    public function store(Request $request){
    if(Auth::user()->level=="petugas"){
      $validator=Validator::make($request->all(),
        [
          'id_jenis'=>'required',
          'id_trans'=>'required',
          'qty'=>'required'
        ]
      );

      if($validator->fails()){
        return Response()->json($validator->errors());
      }
      $id_jenis = $request->id_jenis;
        $harga = DB::table('jenis_cuci')->where('id',$id_jenis)->first();
        $harga_total = $harga->harga_per_kilo;
        //var_dump($harga);
        $subtotal = $harga_total*$request->qty;
        //print_r($subtotal);
        
        //print_r($subtotal);
      $simpan=Detail_transaksi::create([
        'id_jenis'=>$request->id_jenis,
        'id_trans'=>$request->id_trans,
        'qty'=>$request->qty,
        'subtotal'=>$subtotal
      ]);
      $status=1;
      $message="Detail Transaksi Berhasil Ditambahkan";
      if($simpan){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    }
    else{
        return response()->json(['status'=>'Anda bukan petugas']);
        }
    }

    public function update($id,Request $request){
      if(Auth::user()->level=="petugas"){
      $validator=Validator::make($request->all(),
        [
            'id_jenis'=>'required',
            'id_trans'=>'required',
            'qty'=>'required',
            'subtotal'=>'required'
        ]
    );

    if($validator->fails()){
      return Response()->json($validator->errors());
    }

    $ubah=Detail_transaksi::where('id',$id)->update([
        'id_jenis'=>$request->id_jenis,
        'id_trans'=>$request->id_trans,
        'qty'=>$request->qty,
        'subtotal'=>$request->subtotal
    ]);
    $status=1;
    $message="Detail Transaksi Berhasil Diubah";
    if($ubah){
      return Response()->json(compact('status','message'));
    }else {
      return Response()->json(['status'=>0]);
    }
  }
    else{
      return response()->json(['status'=>'anda bukan petugas']);
      }
  }
  public function destroy($id){ 
    if(Auth::user()->level=="petugas"){
    $hapus=Detail_transaksi::where('id',$id)->delete();
    $status=1;
    $message="Detail Transaksi Berhasil Dihapus";
    if($hapus){
      return Response()->json(compact('status','message'));
    }else {
      return Response()->json(['status'=>0]);
    }
  }
  else{
    return response()->json(['status'=>'anda bukan petugas']);
  }
}
}
    

    
