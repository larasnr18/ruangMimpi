<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <title>{{ config('app.name', 'Ruang Mimpi') }}</title> -->
    <title>Ruang Mimpi Ticket Reservation</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/custom.css')}}"/>
    <link rel="shortcut icon" type="images/x-icon" href="images/logo.png">
    
    <style>

$input-borderColor: #ccc;
$color-primary: green;

.form-input--file {
	position: relative;
	overflow: hidden;
	padding-right: 120px;
	text-overflow: ellipsis;
	white-space: nowrap;

}

.form-input-file {
	opacity: 0;
	visibility: hidden;
	position: absolute;
	top: 0;
	left: 0;
}

.form-input--file-button {
	cursor: pointer;
	display: inline-block;
	position: absolute;
	top: 0;
	right: -1px;
	bottom: 0;
	padding: .44em 1em 0;
	background-color: lighten($input-borderColor, 5%);
	
	&:hover {
		background-color: $input-borderColor;
	}
}
    </style>

</head>
<body>

    <div id="app">
        

        <main>
            @yield('content')
            
        </main>
    </div>

    
</body>
</html>

<script>
  $(document).ready(function(){
    $("#beli-tiket").click(function(){
      $("#panel").slideToggle("slow");
    });
  });

  $(document).ready(function(){
    $("#btn-modal-pesan-tiket-success").click(function(){
      location.reload();
    });
  });

  $(document).ready(function(){
    
    $('ul.tabs li').click(function(){
      var tab_id = $(this).attr('data-tab');

      $('ul.tabs li').removeClass('current');
      $('.tab-content').removeClass('current');

      $(this).addClass('current');
      $("#"+tab_id).addClass('current');
    });
  });

  jQuery(document).ready(function(){
    jQuery('#unggah-pembuktian').click(function(e){
      e.preventDefault();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });
      jQuery.ajax({
        url: "{{ url('/unggah') }}",
        method: 'get',
        data: {
          kode_pembayaran: jQuery('#kode-pembayaran').val(),
          bukti_pembayaran: jQuery('#customFile').val()
        },
        dataType: 'json',
        success: function(response){  
          console.log(response.success);
          console.log('success');
          //  console.log(jQuery('#customFile')[0].files[0].size);

          if(response.success == true && jQuery('#customFile')[0].files[0].size <= 2000000) {
            $("#modal-konfirmasi-bayar-success").modal({
              backdrop: 'static',
              keyboard: false
            });
          } else if(response.success == 'hangus') {
            $("#modal-konfirmasi-bayar-hangus").modal();
          } else {
            // console.log(jQuery('#customFile')[0].files[0].size);
            $("#modal-konfirmasi-bayar-fail").modal();
          } 
        },
        error: function(response){
          console.log(response.success);
          console.log('fail');
          $("#modal-konfirmasi-bayar-fail").modal();
        }
      });
    });
  });

  // ------------------------------------------------------------------------------

  jQuery(document).ready(function(){
    jQuery('#buy-ticket').click(function(e){
      e.preventDefault();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
      });
      jQuery.ajax({
        url: "{{ url('/cek-tiket') }}",
        method: 'get',
        data: {
          nama_depan: jQuery('#nama_depan').val(),
          nama_belakang: jQuery('#nama_belakang').val(),
          email: jQuery('#email').val(),
          noHP: jQuery('#noHP').val(),
          jumlah_tiket: jQuery('#jumlah_tiket').val(),
          jenis_tiket: jQuery('#jenis_tiket').val(),
          rujukan: jQuery('#rujukan').val()
        },
        dataType: 'json',
        success: function(response){  
          console.log(response.success);
          console.log('success');

          if(response.success == true) {
            console.log('1');
            $("#modal-pesan-tiket-success").modal({
              backdrop: 'static',
              keyboard: false
            });
            $("#kode-booking").text(response.kode);
            $("#jenis").text(response.jenis_tiket);
            if(response.tipe_tiket == 'early') {
              $("#tipe").text('Early Bird');
            } else {
              $("#tipe").text('Normal');
            }
            // $("#tipe").text(response.tipe_tiket);
            $("#harga").text(response.harga);
            $("#jumlah").text(response.jumlah);
            $("#total").text(response.total);
          } else if(response.success == false){
            console.log('2');
            $("#modal-pesan-tiket-fail").modal();
          } else {
            //PERLU DITAMBAHIN MODAL INI
            $("#modal-pesan-tiket-habis").modal();
          }
          },
        error: function(response){
          console.log('fail');
          $("#modal-pesan-tiket-fail").modal();
        }
      });
    });
  });

  function getFileData(object){
  var file = object.files[0];
  var name = file.name;//you can set the name to the paragraph id 
  document.getElementById('file_name').innerHTML+name;
  document.getElementById('file_name2').innerHTML+name;//set name using core javascript

  }


</script>
