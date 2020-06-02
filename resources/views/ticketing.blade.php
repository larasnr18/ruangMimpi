@extends('layouts.app')

@section('content')
  <div class="container-fluid">

    <?php 
        $datangVip = DB::table('penonton')
            ->join('tiket','penonton.kode','=','tiket.kode')
            ->where('tiket.jenis_tiket','VIP')
            ->sum('penonton.jumlah_hadir');

        $datangReg = DB::table('penonton')
            ->join('tiket','penonton.kode','=','tiket.kode')
            ->where('tiket.jenis_tiket','REG')
            ->sum('penonton.jumlah_hadir');

        $belumVip = DB::table('tiket')
            ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
            ->where('bukti.verifikasi','sudah')
            ->where('tiket.jenis_tiket','VIP')
            ->sum('tiket.jumlah_tiket');

        $belumReg = DB::table('tiket')
            ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
            ->where('bukti.verifikasi','sudah')
            ->where('tiket.jenis_tiket','REG')
            ->sum('tiket.jumlah_tiket');
            
    ?>


    <div class="row">
        <div class="col-md-2 col-12 text-center">
            <h5 class="my-4">Data Kehadiran</h5>
            <div class="card border-info mb-3" style="max-width: 18rem;">
                <div class="card-header">Datang</div>
                <div class="card-body py-0 text-info">
                    <h5 class="card-title">VIP: <?php echo $datangVip ?></h5>
                    <h5 class="card-title">REG: <?php echo $datangReg ?></h5>
                </div>
            </div>
            <div class="card border-info mb-3" style="max-width: 18rem;">
                <div class="card-header">Belum Datang</div>
                <div class="card-body py-0 text-info">
                    <h5 class="card-title">VIP: <?php echo $belumVip - $datangVip ?></h5>
                    <h5 class="card-title">REG: <?php echo $belumReg - $datangReg ?></h5>
                </div>
            </div>
        </div>
        <div class="section1 col-md-10 col-12 mt-4">
            <h3 class="text-center">Daftar Penonton</h3>

            <div class="mt-3">
                <input type="text"  id="cari-data" name="keyword" class="form-control" placeholder="Cari..." onkeyup="cariData2()" >
            </div>

            <div class="table-responsive isi">
                <table class="table table-striped" id="tabel-penonton">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Nomor HP</th>
                            <th>Jenis Tiket</th>
                            <th>Datang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $d)
                        <tr>
                            <td>{{$d->kode}}</td>
                            <td>{{$d->nama}}</td>
                            <td>{{$d->nomor_hp}}</td>
                            <td>{{$d->jenis_tiket}}</td>
                                <td>
                                    <form id = "testing" method = "post" action = "/perbarui">
                                    @csrf
                                        <!-- <select class="form-control" method="post" name = "status"> -->
                                        <select class="form-control form-control-sm" name = "status">
                                            <option value="<?php echo "0" . "." . $d->kode; ?>">0</option>
                                            <?php 
                                                $info = DB::table('penonton')
                                                    ->select('jumlah_hadir')
                                                    ->where('kode',$d->kode)
                                                    ->first();
                                                $var = $info->jumlah_hadir;
                                                
                                                $sisa = $d->jumlah_tiket - $var;
                                                $i = 1;

                                                while ($i <= $sisa){
                                                    ?>
                                                    <option id = "kehadiran" value="<?php echo "$i" . "." . $d->kode; ?>"><?php echo $i; ?></option>
                                                    <?php
                                                    $i = $i + 1; 
                                                }
                                            ?>
                                        </select>
                                    </form>
                                </td>
                            <td>
                                <input form = "testing" id = "jumlah-penonton-hadir" type="submit"  class="btn btn-sm btn-primary" style="background-color:#314455;color:white;" value = "Simpan"/>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    
                                        <!-- Intinya aing mau ngirim <option id="kehadiran"> ke TiketingController.php -->



                    <!-- <tbody>
                        <tr>
                            <td>LF3923U</td>
                            <td>John</td>
                            <td>Doe</td>
                            <td>3</td>
                            <td>2</td>
                            <td><select class="form-control" name = "status">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                        
                                </select></td>
                            <td>
                                <select class="form-control" name = "status">
                                    <option value="N">N</option>
                                    <option value="Y">Y</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>MF3923U</td>                    
                            <td>Mary</td>
                            <td>Moe</td>
                            <td>3</td>
                            <td>2</td>
                            <td><select class="form-control" name = "status">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                        
                            </select></td>
                            <td><select class="form-control" name = "status">
                                    <option value="N">N</option>
                                    <option value="Y">Y</option>
                            </select></td>
                        </tr>
                        <tr>
                            <td>KF3923U</td>    
                            <td>July</td>
                            <td>Dooley</td>
                            <td>3</td>
                            <td>2</td>
                            <td><select class="form-control" name = "status">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                        
                            </select></td>
                            <td><select class="form-control" name = "status">
                                    <option value="N">N</option>
                                    <option value="Y">Y</option>
                            </select></td>
                        </tr>
                    </tbody> -->
                </table>
            </div> 
        </div>
    </div>
  </div>
@endsection