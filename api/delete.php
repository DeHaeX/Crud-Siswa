<?php
require('../config/config.php');

if(isset($_GET['nis'])){
    $safe = htmlspecialchars($_GET['nis']);
    try {
        $addNis = $pdo->prepare('DELETE FROM tb_siswa WHERE nis = :nis'); 
        $addNis->bindValue(':nis', $safe);
        $addNis->execute();
        $row = $addNis->rowCount();

        if($row > 0){
            echo json_encode([
                'status' => 'success',
                'text' => 'Berhasil Menghapus Siswa'
            ]);
        }else{
            echo json_encode([
                'status' => 'error',
                'text' => 'Gagal Menghapus Siswa Ini'
            ]);
            
        }
    } catch (\Throwable $th) {
        echo json_encode([
            'status' => 'error',
            'text' => 'Kesalahan Pada Sistem'
        ]);
    }
}