<?php
include "koneksi.php";
$kode = @$_GET['id'];

$siswa = mysqli_query($conn, "SELECT nis FROM tb_siswa where id_siswa = '$kode'") or die(mysqli_error($conn));
$data = mysqli_fetch_array($siswa);

mysqli_query($conn, "delete from tb_pengguna where username = '$data[nis]'") or die(mysqli_error($conn));
mysqli_query($conn, "delete from tb_siswa where id_siswa = '$kode'") or die(mysqli_error($conn));

?>
<script type="text/javascript">
    alert("Data berhasil dihapus")
    window.location = "index.php?page=lihatsiswa";
</script>