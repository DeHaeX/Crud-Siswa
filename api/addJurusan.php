<?php
require('../config/config.php');

if(isset($_GET['jurusan'])){
    $safe = htmlspecialchars($_GET['jurusan']);
    try {
        $addJurusan = $pdo->prepare('INSERT INTO tb_jurusan (`jurusan`) VALUES (:jurusan)'); 
        $addJurusan->bindParam(':jurusan', $safe);
        $addJurusan->execute();
        $row = $addJurusan->rowCount();

        if($row > 0){
            echo json_encode([
                'status' => 'success',
                'text' => 'Berhasil Menambahkan Jurusan Baru'
            ]);
        }else{
            echo json_encode([
                'status' => 'error',
                'text' => 'Gagal Menambahkan Jurusan Baru'
            ]);
            
        }
    } catch (\Throwable $th) {
        echo json_encode([
            'status' => 'error',
            'text' => 'Gagal Menambahkan Jurusan'
        ]);
    }
}