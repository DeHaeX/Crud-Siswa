<?php
// require('../../config/config.php');
session_start();
$dsn = 'mysql:host=localhost;dbname=crud_siswa';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_GET['username']) && isset($_GET['password']) && isset($_SESSION['status']) == null && isset($_GET['validasi'])){
    $safePass = md5($_GET['password']);
    try {
        $doLogin = $pdo->prepare('SELECT * FROM tb_user WHERE username = :username and password = :password');
        $doLogin->bindParam(':username', $_GET['username']);
        $doLogin->bindParam(':password', $safePass);
        $doLogin->execute();
        $row = $doLogin->rowCount();

        if($row > 0 && $_GET["validasi"] == "crud_akbar_tkjclub"){
            echo json_encode([
                'status' => 'success',
                'text' => 'Login Berhasil'
            ]);
            $_SESSION['status'] = 'has_login';
            $_SESSION['username'] = $_GET['username'];
        }else{
            echo json_encode([
                'status' => 'error',
                'text' => 'Username Atau Password Tidak Valid'
            ]);
        }
    } catch (\Throwable $th) {
        echo json_encode([
            'status' => 'error',
            'text' => 'Ada Kesalahan Pada Sistem'
        ]);
        //throw $th;
    }
}else{
    echo json_encode([
        'status' => 'error',
        'text' => 'Login Gagal, Anda Sudah Login Sebelumnya'
    ]);
}