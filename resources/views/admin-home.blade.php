@extends('layouts.app')

@section('content')

<div class="section1 container-fluid mt-4">

    <?php 
    
    $jumReg = DB::table('tiket')
        ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
         ->where('bukti.verifikasi','sudah')
        ->where('jenis_tiket','REG')
        ->where('aktif','yes')
        ->sum('jumlah_tiket');


    $jumVip = DB::table('tiket')
        ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
        ->where('bukti.verifikasi','sudah')
        ->where('jenis_tiket','VIP')
        ->where('aktif','yes')
        ->sum('jumlah_tiket');

        $tertundaVip = DB::table('tiket')
                    ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
                    ->where('bukti.verifikasi','belum')
                    ->where('tiket.jenis_tiket','VIP')
                    ->where('aktif','yes')
                    ->sum('tiket.jumlah_tiket');

        $tertundaReg = DB::table('tiket')
                    ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
                    ->where('bukti.verifikasi','belum')
                    ->where('tiket.jenis_tiket','REG')
                    ->where('aktif','yes')
                    ->sum('tiket.jumlah_tiket');

        $sisaVip = 100 -  $tertundaVip - $jumVip;
        $sisaReg = 200 - $tertundaReg - $jumReg;

    ?>

    <div class="row">
        <div class="text-center col-md-2 col-12">
            <h5 class="my-2">Ticket Tracker</h5>
            <div class="card border-info mb-3" style="max-width: 18rem;">
                <div class="card-header">Belum Terjual</div>
                <div class="card-body py-0 text-info">
                    <h5 class="card-title">VIP: <?php echo $sisaVip ?></h5>
                    <h5 class="card-title">REG: <?php echo $sisaReg ?></h5>
                </div>
            </div>
            <div class="card border-info mb-3" style="max-width: 18rem;">
                <div class="card-header">Tertunda</div>
                <div class="card-body py-0 text-info">
                    <h5 class="card-title">VIP: <?php echo $tertundaVip ?></h5>
                    <h5 class="card-title">REG: <?php echo $tertundaReg ?></h5>
                </div>
            </div>
            <div class="card border-info mb-3" style="max-width: 18rem;">
                <div class="card-header">Terjual</div>
                <div class="card-body py-0 text-info">
                    <h5 class="card-title">VIP: <?php echo $jumVip ?></h5>
                    <h5 class="card-title">REG: <?php echo $jumReg ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-12">
            <h3 class="text-center">Data Pendaftar</h3>

            <input type="text"  id="cari-data" name="keyword" class="form-control" placeholder="Cari..." onkeyup="cariData()" >

            <div class="table-responsive isi">
                <table class="table table-striped" id="tabel-pendaftar">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Kode P.</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Tiket</th>
                            <th>Rujukan</th>
                            <th>Total</th>
                            <th>Verifikasi</th>
                            <th>Aktif</th>
                            <th>Bukti</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)

                        <?php 
                            $status = DB::table('bukti')
                                ->select('verifikasi')
                                ->where('kode',$d->kode_pembayaran)
                                ->get();

                                if($d->jenis_tiket == "REG"){
                                    if($d->tipe_tiket == "early") {
                                        $harga = 75000;
                                        $total = $d->jumlah_tiket*$harga;
                                    }else {
                                        $harga = 85000;
                                        $total = $d->jumlah_tiket*$harga;
                                    }
                                }else {
                                    if($d->tipe_tiket == "early") {
                                        $harga = 115000;
                                        $total = $d->jumlah_tiket*$harga;
                                    }else {
                                        $harga = 125000;
                                        $total = $d->jumlah_tiket*$harga;
                                    }
                                }

                        ?>

                        <tr>
                            <td>{{$d -> kode}}</td>
                            <td>{{$d -> kode_pembayaran}}</td>
                            <td>{{$d -> nama}}</td>
                            <td>{{$d -> email}}</td>
                            <td>{{$d -> jumlah_tiket}} {{$d -> jenis_tiket}} {{$d -> tipe_tiket}}</td>
                            <td>{{$d -> kode_penyanyi}}</td>
                            <td><?php echo $total?></td>
                            <td><?php echo $status[0]->verifikasi?></td>
                            <td>sdaa</td>
                            <td><button onclick="document.getElementById('myImg').src='{{$d->gambar}}'" class="btn btn-sm btn-primary" id="{{$d->kode}}" data-toggle="modal" data-target="#modal-konfirmasi-bayar" >Lihat Bukti</button></td>            
                            <td>
                            <a href="/admin-home/gantiStatus/{{$d->kode_pembayaran}}" class="btn btn-sm btn-primary">Ganti Status</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
    
    <div class="modal fade" id="modal-konfirmasi-bayar" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="labelModal">Bukti Bayar</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body text-center">
            <img id="myImg" src="" alt="" width="100%">
            </div>
            <div class="modal-footer">
            <button type="button" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal">OK</button>
            </div>
        </div>
        </div>
    </div>
</div>


@endsection
