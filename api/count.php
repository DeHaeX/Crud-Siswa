<?php
require('../config/config.php');

if(isset($_GET['status'])){
    $getCount = $pdo->prepare('SELECT status, COUNT(*) AS count FROM tb_siswa GROUP BY status');
    // $getCount->bindParam(':status', $safe);
    $getCount->execute();
    
    $status_false = 0;
    $status_true = 0;
    
    while ($row = $getCount->fetch(PDO::FETCH_ASSOC)) {
        if($row['status'] == 0){
            $status_false = $row['count'];
        }else{
            $status_true = $row['count'];
        }
    }
    
    echo  json_encode([
        'status_true' => $status_true,
        'status_false' => $status_false,
    ]);
}else if(isset($_GET['jurusan'])){
    $getJurusan = $pdo->prepare('SELECT jurusan FROM tb_jurusan'); // Ambil daftar jurusan dari tabel tb_jurusan
    $getJurusan->execute();

    // Inisialisasi array asosiatif untuk menyimpan jumlah siswa per jurusan
    $jumlahSiswaPerJurusan = array();

    // Inisialisasi variabel untuk menyimpan total jumlah siswa
    $totalSiswa = 0;

    // Inisialisasi array untuk menyimpan hasil akhir dalam bentuk [jumlah_jurusan1, jumlah_jurusan2, ...]
    $resultArray = array();

    // Mengisi array asosiatif dengan 0 untuk setiap jurusan
    while ($row = $getJurusan->fetch(PDO::FETCH_ASSOC)) {
        $jumlahSiswaPerJurusan[$row['jurusan']] = 0;
    }

    // Mengambil jumlah siswa untuk setiap jurusan dari tabel tb_siswa
    $getCount = $pdo->prepare('SELECT jurusan, COUNT(*) AS count FROM tb_siswa GROUP BY jurusan');
    $getCount->execute();

    // Memperbarui nilai jumlah siswa per jurusan sesuai dengan hasil query
    while ($row = $getCount->fetch(PDO::FETCH_ASSOC)) {
        // Memeriksa apakah jurusan dalam hasil query ada dalam daftar jurusan dari tb_jurusan
        if (isset($jumlahSiswaPerJurusan[$row['jurusan']])) {
            $jumlahSiswaPerJurusan[$row['jurusan']] = $row['count'];
            $totalSiswa += $row['count']; // Menambahkan jumlah siswa ke total
        }
    }

    // Mengisi array hasil akhir dengan jumlah siswa untuk setiap jurusan
    foreach ($jumlahSiswaPerJurusan as $jurusan => $jumlah) {
        array_push($resultArray, $jumlah);
    }

    // Mengembalikan hasil dalam bentuk JSON
    echo json_encode($resultArray);
}
