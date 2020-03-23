<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Jenis_cuci;
use Auth;

class JenisCuciController extends Controller
{
    public function index($id){
            $jenis_cuci=DB::table('jenis_cuci')
            ->where('jenis_cuci.id',$id)
            ->get();
            return response()->json($jenis_cuci); 
    }
    public function store(Request $req){
        if(Auth::user()->level=="admin"){
        $validator = Validator::make($req->all(),
        [
            'nama_jenis' => 'required',
            'harga_per_kilo' => 'required'
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson(),400);
        }

        $simpan = Jenis_cuci::create([
            'nama_jenis' => $req->nama_jenis,
            'harga_per_kilo' => $req->harga_per_kilo
        ]);
        $status = 1;
        $message = "Data cucian berhasil ditambahkan";
        if($simpan){
            return Response()->json(compact('status', 'message'));
        }else {
            return Response()->json(['status'=> 0]);
        }
    }
    else {
        return response()->json(['status'=>'anda bukan admin']);
    }
}
    public function update($id, Request $req)
    {
        if(Auth::user()->level=="admin"){
        $validator=Validator::make($req->all(),
        [
            'nama_jenis' => 'required',
            'harga_per_kilo' => 'required'
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson(),400);
        }
        $ubah=Jenis_cuci::where('id',$id)->update([
            'nama_jenis' => $req->nama_jenis,
            'harga_per_kilo' => $req->harga_per_kilo
        ]);
        $status = 1;
        $message = "Data cucian berhasil diubah";
        if($ubah){
            return Response()->json(compact('status', 'message'));
        }else {
            return Response()->json(['status'=> 0]);
        }
    }
    else {
        return response()->json(['status'=>'anda bukan admin']);
    }
}
    public function tampil(){
        $data = Jenis_cuci::get();
        $count = $data->count();
        $jenis = array();
        foreach ($data as $d){

            $jenis[] = array(
                'id' => $d->id,
                'nama_jenis' => $d->nama_jenis,
                'harga_per_kilo' => $d->harga_per_kilo
            );
        }
        return Response()->json(compact('jenis','count'));
    }
    public function destroy($id)
    {
        if(Auth::user()->level=="admin"){
        $hapus=Jenis_cuci::where('id',$id)->delete();
        $status = 1;
        $message = "Data cucian berhasil dihapus";
        if($hapus){
            return Response()->json(compact('status', 'message'));
        }else {
            return Response()->json(['status'=> 0]);
        }
    }
    else {
        return response()->json(['status'=>'anda bukan admin']);
    }
    }

}
