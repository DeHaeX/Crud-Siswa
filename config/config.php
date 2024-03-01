<?php
session_start();
$dsn = 'mysql:host=localhost;dbname=crud_siswa';
$username = 'root';
$password = '';

try{
    if(isset($_SESSION['username'])){
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }else{
        header('Location: /login');
    }
}catch (PDOException $e){
    echo 'Gagal Koneksi Ke DB';
}