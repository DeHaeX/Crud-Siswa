<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Siswa</title>
  <!-- <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"> -->
  <link rel="stylesheet" href="/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/bootstrap/icon/bootstrap-icons.min.css">
</head>

<body>
  <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark" aria-label="Fourth navbar example">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Detail Siswa</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="/">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/detail">Detail Siswa</a>
          </li>
          <li class="nav-item">
            <button onclick="logout()" class="btn btn-danger mt-2"><i class="bi bi-box-arrow-left me-2"></i>Logout</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-5 pt-3">
    <div class="container mt-5">
      <div class="d-flex">
        <a href="/add" class="btn btn-primary w-50 p-2 mb-2">Tambah Siswa</a>
        <form method="get" class="d-flex ms-2 mb-2">
          <input type="text" name="q" id="" class="form-control" value="<?= (isset($_GET['q'])) ? $_GET['q'] : '' ?>" placeholder="Cari Siswa...">
          <button class="btn btn-outline-success ms-1"><i class="bi bi-search"></i></button>
        </form>
      </div>
      <table class="table table-bordered">
        <thead class="bg-dark text-white">
          <tr>
            <th scope="col" class="text-center">NO</th>
            <th scope="col" class="text-center">Nama</th>
            <th scope="col" class="text-center">NIS</th>
            <th scope="col" class="text-center">Kelas</th>
            <th scope="col" class="text-center">Jurusan</th>
            <th scope="col" class="text-center">Info</th>
          </tr>
        </thead>
        <tbody id="table">
          <?php
          include('../config/config.php');

          $totalRows = $pdo->query('SELECT COUNT(*) FROM tb_siswa')->fetchColumn();
          $perPage;
          if (isset($_GET['show'])) {
            if (!is_numeric($_GET['show']) || $_GET['show'] < 10) {
              $perPage = 10;
            } else {
              $perPage = $_GET['show'];
            }
          } else {
            $perPage = 10;
          }
          $totalPages = ceil($totalRows / $perPage);
          
          if (isset($_GET['page']) && is_numeric($_GET['page'])) {
            $currentPage = max(1, min($_GET['page'], $totalPages));
          } else {
            $currentPage = 1;
          }
          
          $offset = ($currentPage - 1) * $perPage;
          
          if(isset($_GET['q'])){
            $searchKey = $_GET['q'];
            $getData = $pdo->prepare('SELECT * FROM tb_siswa WHERE nis LIKE :query OR nama LIKE :query LIMIT :offset, :perPage');
            $keyword = "%$searchKey%";
            $getData->bindParam(':query', $keyword, PDO::PARAM_STR);
          }else{
            $getData = $pdo->prepare('SELECT * FROM tb_siswa LIMIT :offset, :perPage');
          }
          $getData->bindParam(':offset', $offset, PDO::PARAM_INT);
          $getData->bindParam(':perPage', $perPage, PDO::PARAM_INT);
          $getData->execute();

          if ($getData->rowCount() > 0) {
            $num = ($currentPage - 1) * $perPage;
            while ($row = $getData->fetch(PDO::FETCH_ASSOC)) {
              $num++;
              echo '<tr>';
              echo '<th scope="row" class="text-center">' . $num . '</th>';
              echo '<td id="nama' . $num . '">' . $row['nama'] . '</td>';
              echo '<td id="nis' . $num . '">' . $row['nis'] . '</td>';
              echo '<td id="kelas' . $num . '">' . $row['kelas'] . '</td>';
              echo '<td id="kelas' . $num . '">' . $row['jurusan'] . '</td>';
              echo '<td class="text-center"><button onclick="getData(' . $row['nis'] . ')" data-bs-toggle="modal" data-bs-target="#infoSiswa" class="btn m-0 badge btn-info"><i class="bi bi-info"></i></button></td>';
              echo '</tr>';
            }
          } else {
            echo '<tr>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '<td>Nothig Here</td>';
            echo '<td></td>';
            echo '<td></td>';
            echo '</tr>';
          }


          echo '</tbody>';
          echo '</table>';
          echo '<nav aria-label="Page navigation" class="d-flex flex-column align-items-center w-100">';
          echo '<select id="selectShow" oninput="pagi(this.value)" class="form-control w-50 mb-2" >';
          echo '<option value="default">Tampilkan Per</option>';
          echo '<option id="sel10" ' . (isset($_GET['show']) && $_GET['show'] <= 10  ? 'selected' : '') . ' value="10">Show 10 Data</option>';
          echo '<option id="sel25" ' . (isset($_GET['show']) && $_GET['show'] == 25 ? 'selected' : '') . ' value="25">Show 25 Data</option>';
          echo '<option id="sel50" ' . (isset($_GET['show']) && $_GET['show'] == 50 ? 'selected' : '') . ' value="50">Show 50 Data</option>';
          echo '<option id="sel75" ' . (isset($_GET['show']) && $_GET['show'] == 75 ? 'selected' : '') . ' value="75">Show 75 Data</option>';
          echo '<option id="sel100" ' . (isset($_GET['show']) && $_GET['show'] == 100 ? 'selected' : '') . ' value="100">Show 100 Data</option>';
          echo '<option ' . (isset($_GET['show']) && $_GET['show'] == 99999999 ? 'selected' : '') . ' value="99999999">Show All</option>';
          echo '</select>';
          echo '<ul class="pagination">';
          echo '<li class="page-item ' . ($currentPage == 1 ? 'disabled' : '') . '"><a class="page-link" href="?page=' . max(1, $currentPage - 1) . '&show=' . $perPage . '">Previous</a></li>';
          if ($currentPage > 1) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage - 1) . '&show=' . $perPage . '">' . ($currentPage - 1) . '</a></li>';
          }
          echo '<li class="page-item active"><a class="page-link" href="?page=' . $currentPage . '&show=' . $perPage . '">' . $currentPage . '</a></li>';
          if ($currentPage < $totalPages) {
            echo '<li class="page-item"><a class="page-link" href="?page=' . ($currentPage + 1) . '&show=' . $perPage . '">' . ($currentPage + 1) . '</a></li>';
          }
          echo '<li class="page-item ' . ($currentPage == $totalPages || $totalPages == 0 ? 'disabled' : '') . '"><a class="page-link" href="?page=' . ($currentPage + 1) . '&show=' . $perPage . '">Next</a></li>';
          echo '</ul>';
          echo '</nav>';
          ?>

    </div>
    <div class="modal fade" id="infoSiswa" tabindex="-1" aria-labelledby="infoSiswaLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="infoSiswaLabel">Detail Siswa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td><i class="bi bi-person-fill me-2"></i>Nama Siswa : <span id="namaSiswa">Akbar</span></td>
                </tr>
                <tr>
                  <td><i class="bi bi-card-list me-2"></i>Nomor Induk Siswa : <span id="nis">12345678</span></td>
                </tr>
                <tr>
                  <td><i class="bi bi-house-fill me-2"></i>Kelas : <span id="kelas">XI</span></td>
                </tr>
                <tr>
                  <td><i class="bi bi-book me-2"></i>Jurusan : <span class="jurusan" id="jurusan">TKP ( B )</span></td>
                </tr>
              </tbody>
            </table>
            <div class="d-flex align-items-center">
              <div class="w-50">
                <span class="badge text-dark">
                  <span class="bi bi-circle-fill me-2" id="status"></span>
                </span>
                <span>Status Siswa : </span>
              </div>
              <div class="d-flex w-50 justify-content-end">

                <div for="switch" id="statusOn">Aktif</div>
                <div class="form-check d-flex flex-row-reverse user-select-none form-switch">
                  <input type="checkbox" onclick="statusChange()" id="switchBtn" class="form-check-input">
                </div>
              </div>
            </div>
            <div class="d-flex mt-2">
              <a href="/" id="editSiswa" class="btn btn-warning w-50 p-2 me-1"><i class="bi bi-pencil-fill me-1"></i> Edit Siswa</a>
              <button id="btnDelete" onclick="deleteSiswa(this)" value="0" class="btn btn-danger w-50 p-2 ms-1"><i class="bi bi-trash me-1"></i> Hapus Siswa</button>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="button" id="saveBtn" onclick="editData()" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> -->
  <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/bootstrap/js/swal.js"></script>
  <script>
    // let modalSiswa = document.getElementById('infoSiswa');
    let table = document.getElementById('table');
    let namaSiswa = document.getElementById('namaSiswa');
    let nis = document.getElementById('nis');
    let kelas = document.getElementById('kelas');
    let jurusan = document.getElementById('jurusan');
    let switchBtn = document.getElementById('switchBtn');
    let statusSiswa = document.getElementById('status');
    let statusOn = document.getElementById('statusOn');
    let saveBtn = document.getElementById('saveBtn');
    let btnDelete = document.getElementById('btnDelete');
    let editSiswa = document.getElementById('editSiswa');
    let statusSwitch;
    let pilihan = <?= $perPage ?>;
    let query = <?= (isset($_GET['q'])) ? $_GET['q'] : 'false;' ?>

    let idPilihan = 'sel' + pilihan;
    function pagi(param) {
      if (param !== 'default' && param !== '99999999') {
        window.location = '/detail?show=' + param + <?php if (isset($_GET['page'])) {echo '"&page=' . $_GET['page'] . '"';} else {echo "''";} ?> + <?php if (isset($_GET['q'])) {echo '"&q=' . $_GET['q'] . '"';} else {echo "''";} ?>
      } else if (param == 99999999) {
        Swal.fire({
          title: 'Perhatian',
          text: 'Menampilkan Keseluruhan Data Mungkin Dapat Menyebabkan Lag Yang sangat Signifikan Apakah Anda Yakin?',
          icon: 'warning',
          confirmButtonText: 'Ya, Saya Yakin',
          showCancelButton: true,
          cancelButtonText: 'Tidak',
        }).then((result) => {
          (result.isConfirmed) ? window.location = window.location = '/detail?show=' + param + <?php if (isset($_GET['page'])) {echo '"&page=' . $_GET['page'] . '"';} else {echo "''";} ?> + <?php if (isset($_GET['q'])) {echo '"&q=' . $_GET['q'] . '"';} else {echo "''";} ?> : document.getElementById(idPilihan).selected = true;
        });
      }
    }
    
    function deleteSiswa(param){
      Swal.fire({
        title: 'Yakin?',
        text: 'Apakah Anda Yakin Untuk Menghapus Siswa Ini?',
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Tidak',
        confirmButtonText: 'YAKIN',
        allowOutsideClick: false
      }).then((result) => {
        if(result.isConfirmed){
          fetch('/api/delete.php?nis=' + param.value)
            .then(response => response.json())
            .then(data => {
              Swal.fire({
                title: data.status,
                text: data.text,
                icon: data.status,
                confirmButtonText: 'OK'
            }).then((result) => {
              if(result.isConfirmed){;
                window.location = '/detail'
              }
            });
            });
        }
      });
    }
    function getData(param) {
      fetch('/api/?nis=' + param)
        .then(response => response.json())
        .then(data => {
          namaSiswa.textContent = data.nama
          nis.textContent = data.nis
          kelas.textContent = data.kelas
          btnDelete.value = data.nis
          editSiswa.href = '../edit?nis='+data.nis+'&nama='+data.nama+'&kelas='+data.kelas + '&jurusan=' + data.jurusan
          jurusan.textContent = data.jurusan
          saveBtn.style.display = 'block'
          switchBtn.disabled = false
          if (data.status == 'true') {
            switchBtn.checked = true
            statusSiswa.classList.remove('text-danger')
            statusSiswa.classList.add('text-success')
            statusOn.textContent = 'Aktif'
          } else {
            statusSiswa.classList.remove('text-success')
            statusSiswa.classList.add('text-danger')
            switchBtn.checked = false
            statusOn.textContent = 'Tidak Aktif'
          }
        })
        .catch(error => {
          namaSiswa.textContent = 'Gagal Mendapatkan Nama Siswa'
          nis.textContent = 'Gagal Mendapatkan NIS'
          kelas.textContent = 'Gagal Mendapatkan Kelas'
          jurusan.textContent = 'Gagal Mendapatkan Jurusan'
          statusOn.textContent = 'Tidak Diketahui'
          saveBtn.style.display = 'none'
          switchBtn.disabled = true
        })
    }

    let i = 0;

    function editData(param) {
      if (switchBtn.checked) {
        i = 1
      } else {
        i = 0
      }
      fetch('/api/status.php?nis=' + nis.textContent + '&status=' + i)
        .then(response => response.json())
        .then(data => {
          console.log(data)
          if (data.status == '200') {
            Swal.fire({
              title: 'Berhasil',
              text: 'Berhasil Mengubah Status',
              icon: 'success',
              confirmButtonText: 'OK'
            });
          } else if (data.status == '400') {
            Swal.fire({
              title: 'Gagal',
              text: 'Gagal Mengubah Status',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          } else if (data.status == '404') {
            Swal.fire({
              title: 'Gagal',
              text: 'Gagal Mengubah Status',
              icon: 'error',
              confirmButtonText: 'OK'
            });

          } else if (data.status == '403') {
            Swal.fire({
              title: 'Gagal',
              text: 'Status Tidak Di Ijinkan',
              icon: 'error',
              confirmButtonText: 'OK'
            });

          }
        });
    }

    function statusChange() {
      if (statusSiswa.classList.contains('text-success')) {
        statusSiswa.classList.remove('text-success')
        statusSiswa.classList.add('text-danger')
        statusOn.textContent = 'Tidak Aktif'
      } else {
        statusOn.textContent = 'Aktif'
        statusSiswa.classList.remove('text-danger')
        statusSiswa.classList.add('text-success')
      }
    }
  </script>
</body>

</html>