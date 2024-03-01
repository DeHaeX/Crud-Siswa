<?php
include('../config/config.php');

if(isset($_GET['nis'])) {
    $nis = $_GET['nis'];

    try {
        $updateStatus = $pdo->prepare('UPDATE tb_siswa SET status = :status WHERE nis = :nis');
        $newStatus = $_GET['status'];

        if($newStatus > 1){
            echo json_encode([
             'status' => '403'
            ]);
        }else{
            $updateStatus->bindParam(':status', $newStatus, PDO::PARAM_INT);
            $updateStatus->bindParam(':nis', $nis, PDO::PARAM_STR);
            $updateStatus->execute();

            if($updateStatus->rowCount() > 0) {
               echo json_encode([
                'status' => '200'
               ]);
            } else {
                echo json_encode([
                 'status' => '200'
                ]);
            }
        }

    } catch (PDOException $e) {
        echo json_encode([
         'status' => '400'
        ]);
    }
} else {
    echo json_encode([
     'status' => '404'
    ]);
}
?>
