
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <form class="container mt-3 col-md-9 col-lg-7" method="post">
        <h1 class="text-center">Tambah Siswa Baru</h1>
        <hr>
<?php
require('../config/config.php');

if(isset($_POST['submit'])){
    $safeNama = htmlspecialchars($_POST['nama']);
    $safeNis = htmlspecialchars($_POST['nis']);
    $safeKelas = htmlspecialchars($_POST['kelas']);
    $safeJurusan = htmlspecialchars($_POST['jurusan']);
    // End Of Globals Variable

    $addSiswa = $pdo->prepare('SELECT * FROM tb_siswa WHERE nis = :nis'); 
    $addSiswa->bindValue(':nis', $safeNis);
    $addSiswa->execute();
    $row = $addSiswa->rowCount();

    if($row > 0){
        echo "<div class='alert alert-danger'>NIS : <b><i>".$safeNis."</b></i> Sudah Terdaftar Pada Sistem</div>";
    }else{
        
        if(is_numeric($_POST['nis']) && strlen($_POST['nis']) == 10){
            try {
                $addSiswa = $pdo->prepare('INSERT INTO tb_siswa (`nama`, `nis`, `kelas`, `jurusan`, `status`) VALUES (:nama, :nis, :kelas, :jurusan, 1)'); 
                $addSiswa->bindParam(':nama', $safeNama);
                $addSiswa->bindParam(':nis', $safeNis);
                $addSiswa->bindParam(':kelas', $safeKelas);
                $addSiswa->bindParam(':jurusan', $safeJurusan);
                $addSiswa->execute();
                $row = $addSiswa->rowCount();
    
                if($row > 0){
                    echo "<div class='alert alert-success'>Sukses Menambahkan Siswa Baru</div>";
                }else{
                    echo "<div class='alert alert-danger'>Gagal Menambahkan Siswa Baru</div>";
                }
            } catch (\Throwable $th) {
                echo "<div class='alert alert-danger'>Gagal Kesalahan Pada Sistem</div>".$th;
            }
        }else{
            echo "<div class='alert alert-danger'>INVALID NIS FORMAT</div>";
        }
    }

    
}
?>
        <div class="container">
            <label for="nama">Nama Siswa Baru: <span class="text-danger">*</span></label>
            <input type="text" id="nama" class="form-control mb-2" name="nama" placeholder="Nama Siswa" required>

            <label for="nis">NIS: <span class="text-danger">*</span></label>
            <div class="text-danger small" id="alertNis"></div>
            <input minlength="10" maxLength="10" type="text" onchange="nisValid(this)" name="nis" id="nis" class="form-control mb-2" placeholder="Nomor Induk Siswa" required>

            <label for="kelas">Kelas: <span class="text-danger">*</span></label>
            <select name="kelas" id="kelas" class="form-control mb-2" required>
                <optgroup label="Jenjang Kelas">
                    <option value="X">X</option>
                    <option value="XI">XI</option>
                    <option value="XII">XII</option>
                </optgroup>
            </select>
            <label for="jurusan">Jurusan: <span class="text-danger">*</span></label>
            <select name="jurusan" id="jurusan" class="form-control mb-2" required>
                <optgroup label="Jenjang Keahlian" id="jurusanGrup">

                </optgroup>
            </select>

            <input type="submit" value="Tambah Data" name="submit" class="btn btn-primary w-100 p-2">

        </div>
    </form>
    <script>
        let jurusanGrup = document.getElementById('jurusanGrup')
        function getJurusan() {
            return fetch('/api/countjurusan.php')
                .then(response => response.json())
                .then(result => {
                    let data_1 = result; // Mengambil Array Response dari Parameter result
                    let labels = data_1.map(item => item.jurusan); // mengambil array label
                    // console.log(labels.length);
                    for (let i = 0; i < labels.length; i++) {
                        jurusanGrup.innerHTML += "<option value='"+labels[i]+"'>"+labels[i]+"</option>"
                    }
                });
        }
        function nisValid(element){
            var regex = /^[0-9]+$/;

            if(regex.test(element.value) && element.value.length == 10){
                element.classList.add('is-valid')
                element.classList.remove('is-invalid')
                document.getElementById('alertNis').textContent = ''
            }else if(element.value.length != 10){
                // document.getElementById('alertNis').style.display = 'block !important'
                document.getElementById('alertNis').textContent = 'NIS Length Must 10 Characters'
                element.classList.remove('is-valid')
                element.classList.add('is-invalid')
            }else if(!regex.test(element.value)){
                document.getElementById('alertNis').textContent = 'NIS Must Use Number'
                element.classList.remove('is-valid')
                element.classList.add('is-invalid')
            }
        }
        getJurusan()
    </script>
</body>

</html>