<!DOCTYPE html>
<html>
<head>
	<title>Ruang Mimpi</title>
</head>
<body>

    <form action = "/pesan-tiket/tambah" method = "post">
        @csrf
        Nama Depan <input type="text" name="nama_depan" required="required"> <br/>
        Nama Belakang <input type="text" name="nama_belakang" required="required"> <br/>
		Email <input type="email" name="email" required="required"> <br/>
		No. HP <input type="tel" pattern = "[0-9]{10-12}" name="nomor_hp" required="required"> <br/>
		Jumlah Tiket <input type = "number" min="1" step="1" name="jumlah_tiket" required="required"></textarea> <br/>
        Jenis Tiket
		<select name="jenis_tiket">
            <option value="Reguler">Reguler</option>
            <option value="VIP">VIP</option>
        </select> <br/>
        Rujukan
        <select name = "penyanyi">
            <?php 
                $penyanyi = DB::table('penyanyi')->get();
                foreach ($penyanyi as $row){
                    ?>
                    <option value="<?php echo $row->kode; ?>"><?php echo $row->nama; ?></option>
                    <?php
                    // close while loop 
                }
            ?>
        </select>
        <input type="submit" value="Simpan Data">
    </form>

</body>
</html>