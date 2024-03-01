<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Siswa</title>
  <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="bootstrap/css/bootstrap.css"> -->
  <link rel="stylesheet" href="/bootstrap/icon/bootstrap-icons.min.css">
</head>
<body>
<form class="container mt-3 col-md-9 col-lg-7" method="post">
        <h1 class="text-center <?= (isset($_GET['show'])) ? 'd-none' : ''; ?>">Edit Siswa</h1>
        <hr class="<?= (isset($_GET['show'])) ? 'd-none' : ''; ?>">
        <?php
        require('../config/config.php');

        if(isset($_POST['submit'])){
            $jurusan = htmlspecialchars($_POST['jurusan']);
            $addNis = $pdo->prepare('UPDATE tb_siswa SET nama = :nama, nis = :nis, kelas = :kelas, jurusan = :jurusan WHERE nis = :nis'); 
            $addNis->bindParam(':nama', $_POST['nama'], PDO::PARAM_STR);
            $addNis->bindValue(':nis', $_POST['nis']);
            $addNis->bindParam(':kelas', $_POST['kelas'], PDO::PARAM_STR);
            $addNis->bindParam(':jurusan', $jurusan, PDO::PARAM_STR);
            $addNis->execute();
            $row = $addNis->rowCount();   

            if($row > 0){
                header('Location: /edit?show=true');
            }else{
                echo '<div class="alert alert-danger">Mengedit Siswa Gagal</div>';
            }
        }

        ?>
        <?= (isset($_GET['show'])) ? '<div class="alert alert-success">Berhasil Mengedit Siswa <a href="/detail" class="text-success "><i><b>Kembali Ke Detail</b></i></a></div>' : ''; ?>
        <div class="container <?= (isset($_GET['show'])) ? 'd-none' : ''; ?>">
            <label for="nama">Nama Siswa: <span class="text-danger">*</span></label>
            <input type="text" value="<?= (isset($_GET['nama'])) ? $_GET['nama'] : '' ?>" id="nama" class="form-control mb-2" name="nama" placeholder="Nama Siswa" required>

            <label for="nis">NIS: <span class="text-danger">Hanya Baca</span></label>
            <div class="text-danger small" id="alertNis"></div>
            <input  minlength="10"  readonly maxLength="10" type="text" onchange="nisValid(this)" name="nis" id="nis" class="form-control mb-2" placeholder="Nomor Induk Siswa" required value="<?= (isset($_GET['nis'])) ? $_GET['nis'] : '' ?>">

            <label for="kelas">Kelas: <span class="text-danger">*</span></label>
            <select name="kelas" id="kelas" class="form-control mb-2" required>
                <optgroup label="Jenjang Kelas">
                    <option <?= (isset($_GET['kelas']) && $_GET['kelas'] == 'X') ? 'selected' : '' ?> value="X">X</option>
                    <option <?= (isset($_GET['kelas']) && $_GET['kelas'] == 'XI') ? 'selected' : '' ?>  value="XI">XI</option>
                    <option <?= (isset($_GET['kelas']) && $_GET['kelas'] == 'XII') ? 'selected' : '' ?>  value="XII">XII</option>
                </optgroup>
            </select>
            <label for="jurusan">Jurusan: <span class="text-danger">*</span></label>
            <select name="jurusan" id="jurusan" class="form-control mb-2" required>
                <optgroup label="Jenjang Keahlian" id="jurusanGrup">

                </optgroup>
            </select>

            <input type="submit" value="Simpan Perubahan" name="submit" class="btn btn-primary w-100 p-2">

        </div>
</from>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
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
                        if(labels[i] == '<?= htmlspecialchars($_GET['jurusan']) ?>'){
                            jurusanGrup.innerHTML += "<option selected value='"+labels[i]+"'>"+labels[i]+"</option>"
                        }else{
                            jurusanGrup.innerHTML += "<option value='"+labels[i]+"'>"+labels[i]+"</option>"
                        }
                    }
                });
        }
        getJurusan()
    </script>
</body>
</html>
