<!DOCTYPE html>
<html>
    <head>
        <title>Ruang Mimpi</title>
    </head>
    <body>
        <form action = "/unggah-pembayaran/unggah" method = "post" enctype = "multipart/form-data">
            @csrf
            Kode Pembayaran 
            <input type="text" name="kode_pembayaran"><br>
            Bukti Pembayaran
            <input type="file" name="bukti_pembayaran"><br>
            <input type="submit" value="Simpan Data">
        </form>
    </body>
</html>