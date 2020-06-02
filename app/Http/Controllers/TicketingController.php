<?php 

namespace App\Http\Controllers;

require '../vendor/autoload.php';
use Illuminate\Http\Request;
use DB;

class TicketingController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $data = DB::table('bukti')
            ->join('tiket','bukti.kode','=','tiket.kode_pembayaran')
            ->select('tiket.kode','tiket.nomor_hp','tiket.nama','tiket.jenis_tiket','tiket.jumlah_tiket')
            ->where('bukti.verifikasi','sudah')
            ->get();
        
        return view('ticketing',['data'=>$data]);
    }

    public function update(Request $request) {
        $data = $request->status;
        
        $dataArr = explode(".",$data);
        // dd($dataArr);

        $jum = DB::table('penonton')
            ->select('jumlah_hadir')
            ->where('kode',$dataArr[1])
            ->get();
        
        
        $total = $jum[0]->jumlah_hadir + $dataArr[0];

        DB::table('penonton')
            ->where('kode',$dataArr[1])
            ->update(['jumlah_hadir' => $total]);

            $data = DB::table('bukti')
            ->join('tiket','bukti.kode','=','tiket.kode_pembayaran')
            ->select('tiket.kode','tiket.nomor_hp','tiket.nama','tiket.jenis_tiket','tiket.jumlah_tiket')
            ->where('bukti.verifikasi','sudah')
            ->get();
        
        return view('ticketing',['data'=>$data]);

        return view('ticketing');
    }

}

?>