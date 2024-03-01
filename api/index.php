<?php
include('../config/config.php');
if(isset($_GET['nis'])){
    $safe = htmlspecialchars($_GET['nis']);
    $getData = $pdo->prepare('SELECT * FROM tb_siswa WHERE nis = :nis');
    $getData->bindParam(':nis', $safe);
    $getData->execute();
    $row = $getData->fetch(PDO::FETCH_ASSOC);

    // foreach ($row as $rows) {
        echo json_encode([
            'nama' => $row['nama'],
            'nis' => $row['nis'],
            'kelas' => $row['kelas'],
            'jurusan' => $row['jurusan'],
            'status' => ($row['status'] == 1) ? 'true' : 'false' 
        ]);
    }
