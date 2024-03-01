<?php
require('../config/config.php');

$_SESSION['username'] = '';
$_SESSION['status'] = '';

$logout = session_destroy();

if($logout){
    echo json_encode([
        'title' => 'Berhasil',
        'icon' => 'success',
        'text' => 'Logout Berhasil'
    ]);
    session_start();
    $_SESSION['auto'] = 'false';
}else{
    echo json_encode([
        'title' => 'Gagal',
        'icon' => 'error',
        'text' => 'Logout Gagal'
    ]);
}