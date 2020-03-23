<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Pelanggan;
use Auth;

class PelangganController extends Controller
{
    public function index($id){
            $pelanggan=DB::table('pelanggan')
            ->where('pelanggan.id',$id)
            ->get();
            return response()->json($pelanggan); 
    }
    public function store(Request $req){
        if(Auth::user()->level=="admin"){
        $validator = Validator::make($req->all(),
        [
            'nama' => 'required',
            'alamat' => 'required',
            'telp' => 'required'
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson(),400);
        }

        $simpan = Pelanggan::create([
            'nama' => $req->nama,
            'alamat' => $req->alamat,
            'telp' => $req->telp
        ]);
        $status = 1;
        $message = "Data pelanggan berhasil ditambahkan";
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
            'nama' => 'required',
            'telp' => 'required',
            'alamat' => 'required'
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson(),400);
        }
        $ubah=Pelanggan::where('id',$id)->update([
            'nama' => $req->nama,
            'telp' => $req->telp,
            'alamat' => $req->alamat
        ]);
        $status = 1;
        $message = "Data pelanggan berhasil diubah";
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
        $data = Pelanggan::get();
        $count = $data->count();
        $pelanggan = array();
        foreach ($data as $d){

            $pelanggan[] = array(
                'id' => $d->id,
                'nama' => $d->nama,
                'alamat' => $d->almaat,
                'telp' => $d->telp
            );
        }
        return Response()->json(compact('pelanggan','count'));
    }
    public function destroy($id)
    {
        if(Auth::user()->level=="admin"){
        $hapus=Pelanggan::where('id',$id)->delete();
        $status = 1;
        $message = "Data pelanggan berhasil dihapus";
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
