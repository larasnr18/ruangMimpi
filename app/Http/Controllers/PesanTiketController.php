<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class PesanTiketController extends Controller
{
    public function tambah(Request $request)
    {
        // #Menggabungkan nama
        // $nama_awal = $request->nama_depan;
        // $nama_belakang = $request->nama_belakang;
        // $nama = $nama_awal . " " . $nama_belakang;
        
        // do {
        //     #Generate kode_pembayaran
        //     $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //     $max = mb_strlen($keyspace, '8bit') - 1;
        //     $kode_pembayaran = $keyspace[random_int(0, $max)];
            
        //     for ($i = 0; $i < 5; ++$i) {
        //         $kode_pembayaran .= $keyspace[random_int(0, $max)];
        //     }

        //     #Generate kode
        //     $dt = Carbon::now();
        //     $keyspace = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        //     $max = mb_strlen($keyspace, '8bit') - 1;
        //     $kode = $dt->minute;
        //     $kode .= $keyspace[random_int(0, $max)];
        //     $kode .= $keyspace[random_int(0, $max)];
        //     $kode .= $dt->second;

        //     $status_kode_pembayaran = DB::table('tiket')
        //         ->select('kode_pembayaran')
        //         ->where('kode_pembayaran', '=', $kode_pembayaran)
        //         ->count();

        //     $status_kode = DB::table('tiket')
        //         ->select('kode')
        //         ->where('kode', '=', $kode)
        //         ->count();
            
        // } while(($status_kode_pembayaran > 0) && ($status_kode > 0));

        // DB::table('tiket')->insert([
        //     'kode' => $kode,
        //     'nama' => $nama,
        //     'email' => $request->email,
        //     'nomor_hp' => $request->nomor_hp,
        //     'jumlah_tiket' => $request->jumlah_tiket,
        //     'jenis_tiket' => $request->jenis_tiket,
        //     'undangan' => 'False',
        //     'kode_pembayaran' => $kode_pembayaran,
        //     'kode_penyanyi' => $request->penyanyi
        // ]);

        return redirect('/');
    }

    public function cek(Request $request) {
        $dt = Carbon::now('+7 hours');
        // dd($dt);
        if($request->nama_depan != null && $request->nama_belakang) {
            $emailArr = explode ("@",$request->email);
            $endingArr = explode(".",$emailArr[1]);
            if(count($emailArr) == 2 && count($endingArr) >= 2 && strlen($endingArr[1]) > 0) {
                if(is_numeric($request->noHP) && strlen($request->noHP) > 9 && strlen($request->noHP) < 13){
                    if($request->jumlah_tiket != null && $request->jumlah_tiket <= 10 && $request->jumlah_tiket >= 1) {
                        
                        #Menggabungkan nama
                        $nama_awal = $request->nama_depan;
                        $nama_belakang = $request->nama_belakang;
                        $nama = $nama_awal . " " . $nama_belakang;
                        
                        do {
                            #Generate kode_pembayaran
                            $keyspace = '0123456789abcdefghjkmnopqrstuvwxyzABCDEFGHJKMNOPQRSTUVWXYZ';
                            $max = mb_strlen($keyspace, '8bit') - 1;
                            $kode_pembayaran = $keyspace[random_int(0, $max)];
                            
                            for ($i = 0; $i < 5; ++$i) {
                                $kode_pembayaran .= $keyspace[random_int(0, $max)];
                            }

                            #Generate kode
                            // $dt = Carbon::now();
                            $keyspace = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $max = mb_strlen($keyspace, '8bit') - 1;
                            $kode = $dt->minute;
                            $kode .= $keyspace[random_int(0, $max)];
                            $kode .= $keyspace[random_int(0, $max)];
                            $kode .= $dt->second;

                            $status_kode_pembayaran = DB::table('tiket')
                                ->select('kode_pembayaran')
                                ->where('kode_pembayaran', '=', $kode_pembayaran)
                                ->count();

                            $status_kode = DB::table('tiket')
                                ->select('kode')
                                ->where('kode', '=', $kode)
                                ->count();

                            
                            
                        } while(($status_kode_pembayaran > 0) && ($status_kode > 0));

                        $today = $dt->dayOfYear;
                        $maxEarlyBird = 171;
                        $maxOverall = 201;

                        //update kolom aktif
                        // $data = DB::table('tiket')
                        //     ->select('*')
                        //     ->get();

                        // foreach($data as $d) {
                        //     if($today - $d->hari_pembelian > 2 ) {
                        //         DB::table('tiket')
                        //             ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
                        //             ->where('bukti.kode',$d->kode_pembayaran)
                        //             ->where('bukti.verifikasi','belum')
                        //             ->update(['aktif'=>'no']);
                        //     }
                        // }

                        $jumRegEarly = DB::table('tiket')
                            //->select('SUM(jumlah_tiket)')
                            ->where('tipe_tiket','early')
                            ->where('jenis_tiket','REG')
                            ->where('aktif','yes')
                            ->sum('jumlah_tiket');
                        
                        
                        $jumVipEarly = DB::table('tiket')
                            //->select('SUM(jumlah_tiket)')
                            ->where('tipe_tiket','early')
                            ->where('jenis_tiket','VIP')
                            ->where('aktif','yes')
                            ->sum('jumlah_tiket');
                        
                        $jumRegAll = DB::table('tiket')
                            //->select('SUM(jumlah_tiket)')
                            ->where('jenis_tiket','REG')
                            ->where('aktif','yes')
                            ->sum('jumlah_tiket');
                        
                        $jumVipAll = DB::table('tiket')
                            //->select('SUM(jumlah_tiket)')
                            ->where('jenis_tiket','VIP')
                            ->where('aktif','yes')
                            ->sum('jumlah_tiket');  
                        
                        
                        if($request->jenis_tiket == "REG") {
                            if($maxEarlyBird - $today >= 0 && $jumRegEarly + $request->jumlah_tiket <=100) {
                                $tipe_tiket = "early";
                            } else if($maxOverall - $today >= 0 && $jumRegAll + $request->jumlah_tiket <=200){
                                $tipe_tiket = "normal";
                            } else {
                                $tipe_tiket = "habis";
                            }
                        } else {
                            if($maxEarlyBird - $today >= 0 && $jumVipEarly + $request->jumlah_tiket <=50) {
                                $tipe_tiket = "early";
                            } else if($maxOverall - $today >= 0 && $jumVipAll + $request->jumlah_tiket <=100){
                                $tipe_tiket = "normal";
                            } else {
                                $tipe_tiket = "habis";
                            }
                        }

                        // // CEK TIKET_TIKET
                        if($tipe_tiket != 'habis') {
                            DB::table('tiket')->insert([
                                'kode' => $kode,
                                'nama' => $nama,
                                'email' => $request->email,
                                'nomor_hp' => $request->noHP,
                                'jumlah_tiket' => $request->jumlah_tiket,
                                'jenis_tiket' => $request->jenis_tiket,
                                'undangan' => 'False',
                                'kode_pembayaran' => $kode_pembayaran,
                                'kode_penyanyi' => $request->rujukan,
                                'tipe_tiket' => $tipe_tiket,
                                'hari_pembelian' => $dt->dayOfYear,
                                'aktif' => 'yes'
                            ]);

                            DB::table('bukti')->insert([
                                'kode' => $kode_pembayaran,
                                'verifikasi' => 'belum'
                                //'gambar' => $path
                            ]);

                            if($request->jenis_tiket == "REG"){
                                if($tipe_tiket == "early") {
                                    $harga = 75000;
                                    $total = $request->jumlah_tiket*$harga;
                                }else {
                                    $harga = 85000;
                                    $total = $request->jumlah_tiket*$harga;
                                }
                            }else {
                                if($tipe_tiket == "early") {
                                    $harga = 115000;
                                    $total = $request->jumlah_tiket*$harga;
                                }else {
                                    $harga = 125000;
                                    $total = $request->jumlah_tiket*$harga;
                                }
                            }
                            
                            return response()->json([
                                "success" => true,
                                "harga" => $harga,
                                "total" => $total,
                                "tipe_tiket" => $tipe_tiket,
                                "jenis_tiket" => $request->jenis_tiket,
                                "jumlah" => $request->jumlah_tiket,
                                "kode" => $kode_pembayaran
                            ]);  
                        } else {
                            return response()->json([
                                "success" => 'habis'
                            ]);
                        }
                        
                    }
                } 
            }
        }

        return response()->json([
            "success" => false
            
        ]);  
    }
}