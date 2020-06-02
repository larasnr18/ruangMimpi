<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Carbon\Carbon;
use DB;
use Validator;

class UnggahPembayaranController extends Controller
{


    public function cekKodePembayaran(Request $request){
        $kodePembayaran = $request->kode_pembayaran;
        $statusKodePembayaran = DB::table('tiket')
            ->select('kode_pembayaran')
            ->where('kode_pembayaran', '=', $kodePembayaran)
            ->count();

        if($statusKodePembayaran == 1) {
            $status = TRUE;
        } else {
            $status = FALSE;
        }

        return $status;
    }

    public function unggahBuktiPembayaran(Request $request) {
        $status = $this->cekKodePembayaran($request);

        // $data = DB::table('tiket')
        //     ->select('*')
        //     ->get();

        $dt = Carbon::now('+7 hours');
        $today = $dt->dayOfYear;

        // foreach($data as $d) {
        //     if($today - $d->hari_pembelian > 2 ) {
        //         DB::table('tiket')
        //             ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
        //             ->where('bukti.kode',$d->kode_pembayaran)
        //             ->where('bukti.verifikasi','belum')
        //             ->update(['aktif'=>'no']);
        //     }
        // }

        $data2 = DB::table('tiket')
            ->select('hari_pembelian')
            ->where('kode_pembayaran',$request->kode_pembayaran)
            ->get();
            //dd($data2);
        if($today - $data2[0]->hari_pembelian > 2 ) {
            DB::table('tiket')
                ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
                ->where('bukti.kode',$request->kode_pembayaran)
                ->where('bukti.verifikasi','belum')
                ->update(['aktif'=>'no']);
        }
            

        $aktif = DB::table('tiket')
            ->select('kode_pembayaran')
            ->where('kode_pembayaran', $request->kode_pembayaran)
            ->where('aktif','yes')
            ->count();
        // dd($status);
        
        if ($status == TRUE && $aktif == 1) {
            
            $postData = $request->only('bukti_pembayaran');
            $file = $postData['bukti_pembayaran'];

            // Build the input for validation
            $fileArray = array('image' => $file);

            $validator = Validator::make($fileArray, [
                'image' => 'required'
            ]);

            $str_arr = explode (".", $file);
                
            if($str_arr[1] == "jpeg" || $str_arr[1] == "jpg"|| $str_arr[1] == "png"|| $str_arr[1] == "jbmp"|| $str_arr[1] == "gif"|| $str_arr[1] == "svg") {
                return response()->json([
                    "success" => true
                ]);
            } else {
                return response()->json([
                    "success" => false
                ]);  
            }     
        } else if ($status == TRUE && $aktif == 0) {
            return response()->json([
                "success" => "hangus"
            ]);
        }
        return response()->json([
            "success" => false
        ]);
    }

    public function testing(Request $request) {
        $uploadedFile = $request->file('bukti_pembayaran');
    
        $path = $uploadedFile->store('public/images');
        $path = str_replace('public/','',$path);
    
        $destinationPath = public_path('/images');
        $uploadedFile->move($destinationPath, $path);
    
        $kodePembayaran = $request->kode_pembayaran;
        $status = DB::table('bukti')
            ->select('kode')
            ->where('kode', '=', $kodePembayaran)
            ->count();

        if($status == 1) {
            DB::table('bukti')->where('kode',$kodePembayaran)
                ->update(['gambar'=>$path]);
        } else {
            DB::table('bukti')->insert([
                'kode' => $request->kode_pembayaran,
                'verifikasi' => 'belum',
                'gambar' => $path
            ]);
        }
        
        return redirect('/');
    }
}