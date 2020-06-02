<?php

namespace App\Http\Controllers;

require '../vendor/autoload.php';
use Illuminate\Http\Request;
use DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class VerifikasiPembayaranController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $data = DB::table('tiket')
            ->join('bukti','bukti.kode','=','tiket.kode_pembayaran')
            ->select('bukti.*','tiket.*')
            ->get();
    	return view('admin-home',['data' => $data]);
    }

    public function gantiStatus($kode){
        // dd($kode);
        DB::table('bukti')
            ->where('kode',$kode)
            ->update(['verifikasi' => 'sudah']);

        $info = DB::table('tiket')
            ->select('kode_penyanyi','nama','kode')
            ->where('kode_pembayaran',$kode)
            ->first();

        
        DB::table('penonton')->insert([
             'kode' => $info->kode,
             'kode_penyanyi' => $info->kode_penyanyi,
             'nama' => $info->nama,
             'jumlah_hadir' => 0
        ]);

        $this->kirimEmail($kode);
        return redirect('admin-home');
    }

    public function kirimEmail($kode) {
        
        $meng = DB::table('tiket')
            ->select('nama','email','kode','jenis_tiket','jumlah_tiket')
            ->where('kode_pembayaran', '=' ,$kode)
            ->first();

        $mail = new PHPMailer(true);
        
        // dd($kode);

        try {
            //Server settings
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'telkomunivchoir@gmail.com';                     // SMTP username
            $mail->Password   = 'insanberbudi21';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('daus0827@gmail.com');
            $mail->addAddress($meng->email);     // Add a recipient
            
           

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'E-Tiket Konser Ruang Mimpi';
            $mail->Body    = 'Hi, '.$meng->nama.'.
            <br><br>Terimakasih telah melakukan pembelian tiket Konser Ruang Mimpi.
            <br>Ini adalah E-Tiket kamu. Silahkan baca keterangan di bawah dengan teliti.
            <br>Dan harap bawa e-tiket ini pada saat hari-H untuk registrasi on-site.
            <br><br>

            <html>
            <head>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title>E-Tiket Ruang Mimpi</title>
				<link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
				<style type="text/css">
					.container2{
						font-family: "Poppins", sans-serif;
						color: #FFFFFF;
						letter-spacing: 3px;
						text-align: center;
						position:relative;
						background-color:rgba(0,0,0,0.7);
						width:90%;
						margin-left:5%;border-radius:20px;
					}
					.container1{
						height: 1000px;
						width: 700px;
						margin:auto;
					}
					.background{
						background-image: url("https://drive.google.com/uc?export=view&id=1bt97yLzN44e5ovQLrf3sGBXwcZTu0pQc");
						background-position: center;
					background-repeat: no-repeat;
					background-size: cover;
					height: 1000px;
					}

				</style>
			</head>
			<body>
				<div class="container1">
					<div class="background">
						<div  style="height:240px">
						</div>
						<div class="container2" style="padding-top:20px;padding-bottom:20px" >
							<p style="font-size:35px;margin:0;">E-Tiket Konser Ruang Mimpi</p>	
						</div>
						<div class="container2" style="padding-top:0px;" >
							<hr style="width:80%;margin-top:0px">
							<p style="font-size:35px;margin-bottom:0;margin-top:5px">Kode Booking</p>	
							<p style="font-size:60px;margin:0">'.$meng->kode.'</p>
							<hr style="width:80%;margin-bottom:0;">
						</div>
						<div class="container2" style="padding-top:5px;font-size:25px;" >
								<p style="margin-top:5px;margin-bottom:5px">Jenis Tiket: '.$meng->jenis_tiket.'</p>
								<p style="margin-top:5px;margin-bottom:5px">Jumlah Tiket: '.$meng->jumlah_tiket.'</p>
								
						</div>
					</div>
				</div>

			</body>
			</html>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }



}