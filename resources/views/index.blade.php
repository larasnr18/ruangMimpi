@extends('layouts.header')

@section('content')
<div class="container-fluid p-0 smooth-scroll" 
>
  <div class="section1 row mx-0 spy-btn">
    <div class="col-md-12" style="background-color:rgba(0,0,0,0.8); padding-top:100px;text-align:center;color:white;letter-spacing:3px">
      <p class="tagline mb-0" style="font-size:20px;">4th Annual Concert</p>   
      <p class="mb-0 judul-konser" style="font-size:60px;">RUANG MIMPI</p>
      <p class="tagline mb-0" style="font-size:20px;">Realize Our Melodious Dream
        <br>And
        <br>Precompetition Concert Towards Taipei International Choral Competition 2019</p>   
      <p class="mb-0 judul-konser" style="font-size:60px;">SHANKARA RESWARA</p>
      
      <div style="margin-top:20px;">
      
      <button type="button" class="btn btn-outline-light btn-beli" style="letter-spacing:3px;padding-left:50px;padding-right:50px;margin-right:10px;font-size:20px" id="beli-tiket"><a  class="beli-btn" href="#panel" >Beli Sekarang</a></button> 
      
      <span class=""><a href="https://drive.google.com/file/d/1AUAZzr5_zMqY4rycolF_T_cf9l9wy959/view?usp=sharing" target="_blank" class="info-span badge badge-pill badge-light" style="width:42px;height:42px;font-size:20px;text-align:center"><img src="images/info.png" width="100%" style="margin-top:30%" class="img-span"></a></span>

      </div>
      
    </div>
  </div>

  <?php 
  
    $jumVip = DB::table('tiket')
                ->where('jenis_tiket','VIP')
                ->where('aktif','yes')
                ->sum('jumlah_tiket');
        
    $jumReg = DB::table('tiket')
                ->where('jenis_tiket','REG')
                ->where('aktif','yes')
                ->sum('jumlah_tiket');

    $sisaVip = 100 - $jumVip;
    $sisaReg = 200 - $jumReg;
  
  ?>

  <div class="section2" id="panel" style="display:none">
    <div class="container-fluid px-0">

      <ul class="tabs d-flex">
        <li class="tab-link tab-custom current col-sm-6 mx-0 text-center" data-tab="tab-1">Pesan Tiket</li>
        <li class="tab-link tab-custom col-sm-6 mx-0 text-center" data-tab="tab-2">Konfirmasi Pembayaran</li>
      </ul>

      <div id="tab-1" class="tab-content current">

        <form id = "pesan-tiket" class="container form-group" action="/tambah" method="post">
          <div class="row">
            @csrf

            <div class="col-md-4 col-sm-12 my-2">
              Nama Depan <input id = "nama_depan"type="text" class="form-control" name="nama_depan" required = "required">
            </div>
            <div class="col-md-4 col-sm-12 my-2">
              Nama Belakang <input id = "nama_belakang" type="text" class="form-control" name="nama_belakang" required="required">
            </div>
            <div class="col-md-4 col-sm-12 my-2">
              Email <input id = "email"type="email" name="email" class="form-control" required="required">
            </div>
            <div class="col-md-4 col-sm-12 my-2">
              No. HP <input id = "noHP" type="tel" class="form-control" pattern = "^[0-9]{10,12}" name="nomor_hp" required> 
            </div>
            <div class="col-md-2 col-sm-6 my-2">
              Jumlah tiket <input id = "jumlah_tiket" type = "number" class="form-control" min="1" step="1" name="jumlah_tiket" required></textarea>
            </div>
            <div class="col-md-2 col-sm-6 my-2">
              Jenis tiket
              <select id = "jenis_tiket" class="form-control" name="jenis_tiket">
                <option value="REG">REG (<?php echo $sisaReg?> tiket tersedia)</option>
                <option value="VIP">VIP (<?php echo $sisaVip?> tiket tersedia)</option>
              </select>
            </div>
            <div class="col-md-4 col-sm-12 my-2">
              Rujukan
              <select id = "rujukan" class="form-control" name = "penyanyi" selected = "">
              <option value="AAA">Tidak Dari Siapa-Siapa</option>
              <?php 
                $penyanyi = DB::table('penyanyi')
                            ->orderBy('nama','asc')
                            ->get();
                foreach ($penyanyi as $row){
                    ?>
                    <option value="<?php echo $row->kode; ?>"><?php echo $row->nama; ?></option>
                    <?php
                    // close while loop 
                }
              ?>
            </select>
            </div>
              
            <div class="col-md-4 col-sm-12 offset-md-4 offset-sm-12 mt-4">
            <!-- <input data-backdrop="static" type="submit"  class="btn py-1 px-5" data-toggle="modal" data-target="#modal-pesan-tiket" style="width:100%;background-color:#314455;color:white" value="Simpan Data" label="Kirim"> -->
            <input id = "buy-ticket" type="button" class="btn py-1 px-5" style="width:100%;background-color:#314455;color:white" value="Kirim">
            </div>
          </div>
          
        </form>
      </div>
      <div id="tab-2" class="tab-content">
        <!-- <form id = "unggah-pembayaran"class="container form-group" action ="/unggah" method ="post" enctype = "multipart/form-data"> -->
        <form id = "unggah-pembayaran"class="container form-group" action ="/testing" method ="post" enctype = "multipart/form-data">
          <div class="row">
            @csrf
            <div class="col-md-4 col-sm-12 my-2">
              Kode Pembayaran 
              <input id = "kode-pembayaran" type="text" class="form-control" name="kode_pembayaran" required="required">
            </div>
            <div class="col-md-4 col-sm-12 my-2">
              Bukti Pembayaran (max file size : 2MB)
              <div class="custom-file">
                <!-- <input type="file" class="custom-file-input" id="customFile" name="bukti_pembayaran" onchange='getFileData(this)' required="required">
                
                <div class="custom-file-label" for="customFile" id='file_name'></div> -->

                <input type="file" id="customFile"
                name="bukti_pembayaran" onchange="getFileData(this)" class=" form-control" style="padding-top:3px">
                <div id = 'file_name'></div>
                
              </div>
            </div>
            <div class="col-md-4 col-sm-12 mt-4">
              <!-- <input id = "unggah-pembuktian" type="submit" class="btn py-1 px-5" data-toggle="modal" data-target="#modal-konfirmasi-bayar" style="width:100%;background-color:#314455;color:white" value="Simpan Data"> -->
              <input id = "unggah-pembuktian" type="submit" class="btn py-1 px-5" style="width:100%;background-color:#314455;color:white;margin-top:10px" value="Kirim">
            </div>
          </div>
        </form>
      </div>

    </div><!-- container -->
  </div>

  <div class="section3 pb-5" style="background-color:#314455">
    <div class="container">
      <div class="row">
        <div class="col-12 mt-3 judul" >TENTANG KONSER<hr style="color:white"></div>
        <div class="col-md-6 col-6" id="g1" style="text-align:right">
          <img src="images/logo.png" alt="Ruang Mimpi" width="30%" height="auto" class="gambar" >
        </div>
        <div class="col-md-6 col-6" id="g2" style="text-align:left">
          <img src="images/shankara.png" alt="Shankara Reswara" width="30%" height="auto" class="gambar" >
        </div>
        <div class="col-md-10 offset-md-1 col-sm-12 mt-4 deskripsi" style="color:white;letter-spacing:0.5px">
          Konser yang akan digelar di Jakarta ini merupakan gabungan dari konser tahunan keempat dan konser pra kompetisi menuju Taipei International Choral Competition (TICC) 2019 di Taipei - Taiwan. Kedua rangkaian konser ini juga sebagai rangkaian peringatan 5 tahun eksistensi Telkom University Choir di dunia paduan suara. 
          <br><br>
          Ruang Mimpi: Realize Our Melodious Dream merupakan konser tahunan keempat Telkom University Choir dimana kami hendak menunjukkan keinginan Telkom University Choir untuk terus berkembang menjadi paduan suara yang semakin maju dari apa yang telah kami raih dalam kurun waktu 5 tahun terakhir. Seperti judul yang kami angkat, yaitu Ruang Mimpi, Telkom University Choir diibaratkan sebagai anak kecil berusia 5 tahun yang memiliki berbagai mimpi-mimpi besar di ruang mimpinya yang satu persatu akan diwujudkan pada masa mendatang.
          <br><br>
          Konser pra kompetisi ini merupakan salah satu rangkaian dari program 3rd Amazing Cultural Mission and International Choral Competition: Shankara Reswara. Nama Shankara Reswara sendiri terinspirasi dari bahasa Sansekerta yaitu Shankara dengan arti pembawa keberuntungan dan Reswara dengan arti unggul. Nama tersebut menyimpan do’a Telkom University Choir agar dapat menjadi paduan suara yang “membawa keberuntungan” dan menjadi yang paling “unggul” dalam mengharumkan nama bangsa Indonesia di kancah Internasional. 
          <br><br>
          Adapun tujuan dari konser pra kompetisi adalah sebagai konser evaluasi menuju Taipei International Choral Competition (TICC) 2019 yang akan dilaksanakan pada 30 Juli - 2 Agustus di Taipei, Taiwan. Disamping itu, konser tersebut diharap dapat melatih mental penyanyi untuk perlombaan sekaligus menguji materi-materi yang telah dilatih. Kategori yang akan diikuti oleh Telkom University Choir pada Taipei International Choral Competition 2019 esok di antaranya adalah, Contemporary Music, Youth Choir dan Ethnic/Traditional Music. 
          <br><br>
          Kami juga memohon do’a sekaligus dukungan agar kami dapat meraih prestasi yang membanggakan serta mengharumkan nama Telkom University dan Indonesia di kancah internasional.
        </div>
        
      </div>
    </div>
  </div>

  <div class="section4 pb-5" style="background-color:#172028;color:white;letter-spacing:3px;text-align:center">
    <div class="container">
      <div class="row">
        <div class="col-12 mt-4 judul">INFO KONSER<hr></div>
        
        <div class="col-md-4 col-sm-12 info">
          <img src="images/place-icon.png" alt="Ruang Mimpi"height="auto" class="logo-info"><br><br>
          Auditorium Graha Swara, <br>Universitas Tarumanegara
          <br>Jakarta.<br><br>
        </div>
        <div class="col-md-4 col-sm-12 info">
          <img src="images/date-icon.png" alt="Ruang Mimpi" height="auto" class="logo-info"><br><br>
          Saturday, 20th July 2019.<br><br>
        </div>
        <div class="col-md-4 col-sm-12 info">
          <img src="images/time-icon.png" alt="Ruang Mimpi" height="auto" class="logo-info"><br><br>
          07.30 PM - done.
          <br><br>
        </div>
        <div class="col-md-4 offset-2 col-sm-12 info info-tiket">
          <img src="images/price-icon.png" alt="Ruang Mimpi" height="auto" class="logo-info"><br><br>
          VIP: Rp.125.000,-<br>
          REG: Rp.85.000,-<br>
          <br><br>
        </div>
        <div class="col-md-4 col-sm-12 info">
          <img src="images/info-icon.png" alt="Ruang Mimpi" class="logo-info" height="auto"><br><br>
          <img src="images/line-logo.png" alt="Line" width="5%" height="auto"> LINE: justinardina1 <br>  
          <img src="images/whatsapp-logo.png" alt="Line" width="4%" height="auto"> WA: 082215267396
        </div>
      </div>
    </div>
  </div>

  <div class="footer py-2" style="background-color:#000000;">
    <div class="container">
      <div class="row">  
        <div class="col-6 float-right judul-footer" style="color:white;letter-spacing:3px;text-align:right">
          <p class="mb-0" style="font-size:10px">CONNECT WITH US: </p>
        </div>    
        <div class="col-6 float-left" style="color:#eaeaea;font-size:10px;letter-spacing:30px">
          <a href="https://twitter.com/telkomunivchoir" target="_blank">
            <img src="images/twitter-logo.png" alt="twitter"class="socmed">
          </a>
          <a href="https://www.instagram.com/telkomunivchoir/" target="_blank">
            <img src="images/instagram-logo.png" alt="instagram" class="socmed">
          </a>
          <a href="https://www.youtube.com/channel/UC8v-4wyOT3uBuspN45ye6Lw"target="_blank">
            <img src="images/youtube-logo.png" alt="youtube" class="socmed">
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="modal-pesan-tiket-success" tabindex="-1" role="dialog" aria-labelledby="label-modal" aria-hidden="true">  
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="label-modal">Pemesanan Berhasil</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            Pesanan berhasil disimpan dengan rincian tiket sebagai berikut:
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col">Jenis</th>
                    <th scope="col">Tipe</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th id = "jenis" scope="row"></th>
                    <td id = "tipe" ></td>
                    <td id = "harga" ></td>
                    <td id = "jumlah" ></td>
                    <td id = "total" ></td>
                  </tr>
                </tbody>
              </table>
            </div>            
            Harap simpan/screenshot kode booking dibawah ini, untuk validasi konfirmasi pembayaran. <br><br>
            <h3>KODE BOOKING</h3>
            <div id = "kode-booking" style="font-size:30px;background-color:#314455;color:white;border-radius:0.25rem"></div>
            <div style="font-size:13px">
              <br>Untuk pembayaran dapat dibayarkan secara transfer ke akun rekening bank Mandiri dengan nomor <b>1310013916715</b> a.n. <b>Justina Ardina Yovita Parhusip</b>.
              <br><br>Batas maksimal pembayaran dan pengunggahan bukti bayar adalah <b>2x24</b> jam setelah pemesanan, apabila lebih dari itu maka pesanan dianggap hangus dan Anda diharuskan melakukan pemesanan ulang.
              <br><br>Untuk refund akan dikenakan potongan sebesar <b>30%</b> dari total pesanan dan harap menghubungi CP yang tertera pada website.
              <br>Terimakasih.
            </div>
            
          </div>
          <div class="modal-footer">
            <!-- <button type="submit" form = "pesan-tiket" value = "submit" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal">OK</button> -->
            <!-- <input type="submit" form = "pesan-tiket" value = "submit" class="btn" style="background-color:#314455;color:white;" value = "OK"/> -->
            <input id = "btn-modal-pesan-tiket-success" type="submit" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal" value = "OK"/>
          </div>
        </div>
      </div>
  </div>
  
  <div class="modal fade" id="modal-pesan-tiket-fail" tabindex="-1" role="dialog" aria-labelledby="label-modal" aria-hidden="true">  
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="label-modal">Peringatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            Harap semua kolom diisi dengan benar dan sesuai ketentuan<br><br>
          </div>
          <div class="modal-footer">
            <!-- <button type="submit" form = "pesan-tiket" value = "submit" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal">OK</button> -->
            <input type="submit" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal" value = "OK"/>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="modal-pesan-tiket-habis" tabindex="-1" role="dialog" aria-labelledby="label-modal" aria-hidden="true">  
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="label-modal">Peringatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">
            Tiket yang anda ingin pesan sudah habis!<br><br>
          </div>
          <div class="modal-footer">
            <!-- <button type="submit" form = "pesan-tiket" value = "submit" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal">OK</button> -->
            <input type="submit" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal" value = "OK"/>
          </div>
        </div>
      </div>
  </div>
  
  <div class="modal fade" id="modal-konfirmasi-bayar-success" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="labelModal">Informasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">    
            Sistem akan memvalidasi data, dan apabila sesuai maka E-ticket akan dikirim via email yang telah Anda inputkan pada formulir pendaftaran. Apabila dalam waktu 2x24 jam e-ticket belum diterima, harap hubungi CP yang tertera pada website ini.<br><br>
            Klik tombol "Simpan" untuk menyimpan bukti pendaftaran anda
          </div>
          <div class="modal-footer">
            <!-- <button type="button" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal">OK</button> -->
            <input type="submit" class="btn" form = "unggah-pembayaran" style="background-color:#314455;color:white;" value = "Simpan"/>
          </div>
        </div>
      </div>
    </div>
  
    <div class="modal fade" id="modal-konfirmasi-bayar-fail" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="labelModal">Peringatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">    
            Mohon untuk dicek kembali kode pembayaran. Pastikan file adalah gambar dan tidak melebihi batas maximum file size<br><br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="modal-konfirmasi-bayar-hangus" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="labelModal">Peringatan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body text-center">    
            Kode pembayaran anda telah hangus. Jika ada pertanyaan harap menghubungi CP yang tertera pada website<br><br>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" style="background-color:#314455;color:white;" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
  </div>
</div>

@endsection



