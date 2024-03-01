<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=crud_siswa';
$username = 'root';
$password = '';

$pdo = new PDO($dsn, $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
function passValidation($password){
    if(strlen($password) < 8){
        return 'Password Must 8 Character';
    }
    
    if(!preg_match('/[A-Z]/', $password)){
        return 'Passowrd Must Include Uppercase Character';
    }
    
    if(!preg_match('/[0-9]/', $password)){
        return 'Passowrd Must Include a Number';
    }
    
    if(!preg_match('/[^a-zA-Z0-9]/', $password)){
        return 'Passowrd Must Include a Symbols';
    }

    return 'berhasil';
}
if(isset($_GET['user']) && isset($_GET['pass'])){
        $doLogin = $pdo->prepare('SELECT * FROM tb_user WHERE username = :username');
    $doLogin->bindParam(':username', $_GET['username']);
        $doLogin->execute();
    $row = $doLogin->rowCount();

    if($row > 0){
            echo json_encode([
                'status' => 'error',
                'text' => 'Username Has Already Taken'
            ]);
    }else{
        $pass = $_GET['pass'];
        if(passValidation($pass) == 'berhasil'){
            $hashPass = md5($_GET['pass']);
            try {
                $doLogin = $pdo->prepare('INSERT INTO tb_user (`username`, `password`)VALUES(:username, :password);');
                $doLogin->bindParam(':username', $_GET['user']);
                $doLogin->bindParam(':password', $hashPass);
                $doLogin->execute();
                $row = $doLogin->rowCount();
    
                if($row > 0){
                        echo json_encode([
                            'status' => 'success',
                            'text' => 'Register Berhasil'
                        ]);
                }else{
                        echo json_encode([
                            'status' => 'error',
                            'text' => 'Register Gagal'
                        ]);
                    }
                } catch (\Throwable $th) {
                echo json_encode([
                    'status' => 'error',
                    'text' => 'Register Gagal'
                ]);
            }
        }else{
                echo json_encode([
                    'status' => 'error',
                    'text' => passValidation($pass)
                ]);
            }
        }
}
