<?php 
require 'controller/config/connection.php';
include 'librarys/functionFilter.php';

$kelas = $_POST['ruang_kelas'];
$id_ruang = trim(filterString($_POST['ruang']));

	foreach ($kelas as $k) {
		$konfigRuang = mysqli_query($conn, "INSERT INTO `ruang_kelas`(`id_kelas`, `id_ruang`) VALUES ($k,$id_ruang)");
	}

	if ($konfigRuang) {
		header('location:'.$base_url.'admin/ruang_kelas/?message_success=Selamat, Data Ruang Kelas Berhasil Ditambahkan!.');
	}else{
		header('location:'.$base_url.'admin/ruang_kelas/?message_failed=  Maaf, Data Ruang Kelas gagal ditambahkan!, harap periksa lagi informasi yang diinputkan!.');
	}
 ?>