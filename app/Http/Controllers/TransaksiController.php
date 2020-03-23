<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;
use App\Detail_transaksi;
use App\Pelanggan;
use App\Petugas;
use App\Jenis_cuci;
use JWTAuth;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class TransaksiController extends Controller
{
  public function index($tgl_awal, $tgl_akhir)
  {
      if(Auth::user()->level=="petugas"){
          $transaksi=DB::table('transaksi')
          ->join('pelanggan','pelanggan.id', '=', 'transaksi.id_pelanggan')
          ->join('petugas','petugas.id', '=', 'transaksi.id_petugas')
          ->where('transaksi.tgl_transaksi', '>=', $tgl_awal)
          ->where('transaksi.tgl_transaksi', '<=', $tgl_akhir)
          ->select('transaksi.id','tgl_transaksi','nama','alamat','pelanggan.telp', 'tgl_selesai')
          ->get();

          $datatrans=array(); $no=0;
          foreach ($transaksi as $t){
              $datatrans[$no]['id'] = $t->id;
              $datatrans[$no]['tgl_transaksi'] = $t->tgl_transaksi;
              $datatrans[$no]['nama'] = $t->nama;
              $datatrans[$no]['alamat'] = $t->alamat;
              $datatrans[$no]['telp'] = $t->telp;
              $datatrans[$no]['tgl_selesai'] = $t->tgl_selesai;

              $grand=DB::table('detail_transaksi')->where('id_trans', $t->id)->groupBy('id_trans')
              ->select(DB::raw('sum(subtotal) as grand_total'))->first();

              $datatrans[$no]['grand_total'] = $grand->grand_total;
              $detail=DB::table('detail_transaksi')->join('jenis_cuci','jenis_cuci.id', '=', 'detail_transaksi.id_jenis')
              ->where('id_trans', $t->id)->select('jenis_cuci.nama_jenis', 'jenis_cuci.harga_per_kilo', 'detail_transaksi.qty', 'detail_transaksi.subtotal')->get();

              $datatrans[$no]['detail'] = $detail;
              
          }
          return response()->json($datatrans);
      }else{
          return response()->json(['status'=>'anda bukan petugas']);
      }
  }
    public function store(Request $request){
      if(Auth::user()->level=="petugas"){
      $validator=Validator::make($request->all(),
        [
          'id_petugas'=>'required',
          'id_pelanggan'=>'required',
          'tgl_transaksi'=>'required',
          'tgl_selesai'=>'required'
        ]
      );

      if($validator->fails()){
        return Response()->json($validator->errors());
      }

      $simpan=Transaksi::create([
        'id_petugas'=>$request->id_petugas,
        'id_pelanggan'=>$request->id_pelanggan,
        'tgl_transaksi'=>$request->tgl_transaksi,
        'tgl_selesai'=>$request->tgl_selesai
      ]);
      $status=1;
      $message="Data transaksi Berhasil Ditambahkan";
      if($simpan){
        return Response()->json(compact('status','message'));
      }else {
        return Response()->json(['status'=>0]);
      }
    }
    else {
        return response()->json(['status'=>'anda bukan petugas']);
    }
}

    public function update($id,Request $request){        
    if(Auth::user()->level=="petugas"){
      $validator=Validator::make($request->all(),
        [
            'id_petugas'=>'required',
            'id_pelanggan'=>'required',
            'tgl_transaksi'=>'required',
            'tgl_selesai'=>'required'
        ]
    );
    if($validator->fails()){
        return Response()->json($validator->errors()->toJson(),400);
    }
      $ubah=Transaksi::where('id',$id)->update([
          'id_petugas'=>$request->id_petugas,
          'c'=>$request->id_pelanggan,
          'tgl_transaksi'=>$request->tgl_transaksi,
          'tgl_selesai'=>$request->tgl_selesai
        ]);
          $status = 1;
        $message = "Data transaksi berhasil diubah";
        if($ubah){
            return Response()->json(compact('status', 'message'));
        }else {
            return Response()->json(['status'=> 0]);
        }
    }
    else {
        return response()->json(['status'=>'anda bukan petugas']);
    }
  }
  
  public function destroy($id){
    if(Auth::user()->level=="petugas"){
    $hapus=Transaksi::where('id',$id)->delete();
    $status=1;
    $message="Data Transaksi Berhasil Dihapus";
    if($hapus){
        return Response()->json(compact('status', 'message'));
    }else {
        return Response()->json(['status'=> 0]);
    }
    }
    else {
    return response()->json(['status'=>'anda bukan petugas']);
    }
}
}