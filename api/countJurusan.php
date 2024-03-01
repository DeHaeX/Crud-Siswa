<?php
require('../config/config.php');

$getCount = $pdo->prepare('SELECT jurusan FROM tb_jurusan');
// $getCount->bindParam(':status', $safe);
$getCount->execute();
$result = $getCount->fetchAll(PDO::FETCH_ASSOC);

// echo "<pre>";
// print_r($result);
echo json_encode($result);

