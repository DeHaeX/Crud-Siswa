<?php
session_start();
if (isset($_SESSION['status']) == null) {
    header('Location: /login');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/icon/bootstrap-icons.css">
    <script src="/bootstrap/js/chart.js"></script>
</head>
<style>
    .canvas-container {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>

<body>
    <nav class="navbar fixed-top navbar-expand-md navbar-dark bg-dark" aria-label="Fourth navbar example">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbarsExample04" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/detail">Detail Siswa</a>
                    </li>
                    <li class="nav-item">
                        <button onclick="logout()" class="btn btn-danger mt-2"><i class="bi bi-box-arrow-left me-2"></i>Logout</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="mt-4 pt-3 py-0 my-0">
        <div class="pt-3 canvas-containers">
            <div class="canvas-container py-3 bg-light text-dark" id="dataSiswa">
                <canvas id="siswaChart" width="340" height="300"></canvas>
                <a class="btn btn-primary mt-3 mb-3" href="/detail">Detail Siswa</a>
            </div>
            <div class="canvas-containers">
                <div class="canvas-container py-3 bg-dark text-light" id="dataJurusan">
                    <canvas id="jurusanChart" width="340" height="400"></canvas>
                    <button class="btn btn-primary mt-3 mb-3" data-bs-toggle="modal" data-bs-target="#infoJurusan">Detail Jurusan</button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="infoJurusan" tabindex="-1" aria-labelledby="infoJurusanLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoJurusanLabel">Detail Jurusan</h5>
                    </div>
                    <div class="modal-body d-flex flex-column">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary w-75">List Jurusan</button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" style="height: 200px;  overflow-y: scroll;" id="jurusanList">
                            </ul>
                        </div><!-- /btn-group -->
                        <label for="tambahJurusan" class="mt-3">Tambahkan Jurusan Baru :</label>
                        <input type="text" class="form-control" name="" id="tambahJurusan" placeholder="Masukkan Nama Jurusan Baru">
                        <button class="btn btn-outline-success w-100 p-2 mt-2" onclick="addJurusan()">Tambah Jurusan</button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="konfirmasi()">Tutup</button>
                        <button type="button" disabled id="saveBtn" onclick="saveJurusan()" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="/bootstrap/js/swal.js"></script>
        <script>
            let jurusanList = document.getElementById('jurusanList')
            let tambahJurusan = document.getElementById('tambahJurusan')
            let saveBtn = document.getElementById('saveBtn')
            // Fungsi getStatus() Berfungsi Untuk Mengambil Data Siswa, Antara Aktif Dan Tidak Aktif
            function getStatus() {
                fetch('/api/count.php?status=true')
                    .then(response => response.json())
                    .then(result => {
                        // Menghitung persentase siswa yang aktif dan tidak aktif
                        if(result.status_true == 0 && result.status_false == 0){
                            Swal.fire({
                            title: 'Error',
                            text: 'Gagal Mendapatkan Data Siswa',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        })
                            document.getElementById('dataSiswa').style.display = 'none'
                        }else{
                        var percentage = 100 / (result.status_false + result.status_true);
                        var percentTrue = result.status_true * percentage;
                        var percentFalse = result.status_false * percentage;
                        
                        var data = {
                            // Membuat Labels Untuk Pie Chart labels Ditambahkan Dengan Presentase
                            //  function toFixed(2) berfungsi untuk Menampilkan 2 desimal dibelakang Koma (,)
                            labels: ['Tidak Aktif' + ' ( ' + percentFalse.toFixed(2) + '% )', 'Aktif' + ' ( ' + percentTrue.toFixed(2) + '% )'],
                            datasets: [{
                                // Menggunaan data yang di dapat dari fetch berupa parameters 'result' untuk data
                                data: [result.status_false, result.status_true],
                                backgroundColor: ['#ff6384', '#36a2eb'],
                            }]
                        };

                        // Configurasi Pie Chart
                        var config = {
                            // Jenis Chart
                            type: 'pie',
                            data: data,
                            options: {
                                responsive: false,
                                plugins: {
                                    legend: {
                                        // Posisi Legenda Chart
                                        position: 'right',
                                    },
                                    // Judul Chart
                                    title: {
                                        display: true, // False Jika Ingin Menghilangkan Judul
                                        text: 'Data Siswa' //Judul
                                    }
                                }
                            }
                        };

                        // Mengubah element canvas menggunakan Chart Js (di bagian config)
                        var chartSiswa = new Chart(document.getElementById('siswaChart'), config)
                    }
                    }).catch(error => {
                        // Menampilkan Alert Gagal ( Swal ) Jika Gagal Mengambil data
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal Mendapatkan Data Siswa',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Menghilangkan element #dataSiswa jika gagal fetch data ( agar error tidak terlihat di frontend )
                            document.getElementById('dataSiswa').style.display = 'none'
                        });
                    });
            }

            // Fungsi getJurusanSiswa() Berfungsi Mengambil Data jurusan siswa misal di TKJ ada berapa orang
            function getJurusanSiswa() {
                return fetch('/api/count.php?jurusan=true')
                    .then(response => response.json())
                    .then(result => {
                        // Mengembalikan Nilai Result Agar Bisa Di Ambil Nilainya diluar function
                        return result;
                    });
            }

            let currentJurusan = [];
            // Function ini berfungsi untuk mengambil nama nama jurusan yang ada di database
            function getJurusan() {
                return fetch('/api/countjurusan.php')
                    .then(response => response.json())
                    .then(result => {
                        let data_1 = result; // Mengambil Array Response dari Parameter result
                        let labels = data_1.map(item => item.jurusan); // mengambil array label
                        // console.log(labels.length);
                        for (let i = 0; i < labels.length; i++) {
                            jurusanList.innerHTML += "<li><span class='dropdown-item'>" + labels[i] + "</span></li>"
                            currentJurusan.push(labels[i])
                        }
                        return labels; // Mengembalikan Nilai Labels Agar Bisa Digunakan Di Luar Function
                    });
            }

            let unsaved = [];

            function addJurusan() {
                // console.log(currentJurusan)
                if (tambahJurusan.value != '') {
                    if (unsaved.includes(escapeHTML(tambahJurusan.value)) || currentJurusan.includes(escapeHTML(tambahJurusan.value))) {
                        Swal.fire({
                            title: 'Oopss...',
                            text: 'Jurusan Sudah Ada',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        })
                    } else {
                        Swal.fire({
                            title: 'Berhasil',
                            text: 'Jurusan Baru Berhasil DiTambahkan Sementara, Tekan "Simpan Perubahan" Untuk Menyimpan',
                            icon: 'info',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false
                        })
                        saveBtn.disabled = false
                        unsaved.push(tambahJurusan.value)
                        // console.log(unsaved)
                        jurusanList.innerHTML += "<li><span class='dropdown-item'>" + escapeHTML(tambahJurusan.value) + "<span class='text-danger'>*</span></span></li>";
                        tambahJurusan.value = ''
                    }
                } else {
                    Swal.fire({
                        title: 'Oopss...',
                        text: 'Nama Jurusan Baru Tidak Boleh Kosong',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    })
                }
            }

            function escapeHTML(str) {
                return str.replace(/[&<>"']/g, function(match) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    } [match]
                });
            }

            function logout(){
                return fetch('/api/logout.php')
                    .then(response => response.json())
                    .then(result => {
                        Swal.fire({
                        title: result.title,
                        text: result.text,
                        icon: result.icon,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    }).then((result) => {
                        (result.isConfirmed) ? window.location = '/': ''
                    })
                    });
            }

            function konfirmasi() {
                if (unsaved != '') {
                    Swal.fire({
                        title: 'Yakin?',
                        text: 'Apakah Anda Yakin Untuk Menutup Halaman Ini? Ada Beberapa Data Yang Belum Tersimpan',
                        icon: 'warning',
                        confirmButtonText: 'OK',
                        showCancelButton: true,
                        cancelButtonText: 'Tidak',
                        allowOutsideClick: false
                    }).then((result) => {
                        (result.isConfirmed) ? window.location = '/': ''
                    })
                } else {
                    window.location = '/'
                }
            }

            function saveJurusan() {
                if (unsaved != '') {
                    for (let i = 0; i < unsaved.length; i++) {
                        // console.log(unsaved[i])
                        fetch('/api/addjurusan.php?jurusan=' + unsaved[i])
                            .then(response => response.json())
                            .then(data => {
                                if (data.status == 'success') {
                                    Swal.fire({
                                        title: 'Berhasil',
                                        text: data.text,
                                        icon: data.status,
                                        confirmButtonText: 'OK',
                                        allowOutsideClick: false
                                    }).then((result) => {
                                        if(result.isConfirmed){
                                            window.location = '/'
                                        } 
                                    })
                                } else {
                                    Swal.fire({
                                        title: 'Gagal',
                                        text: data.text,
                                        icon: data.status,
                                        confirmButtonText: 'OK',
                                        allowOutsideClick: false
                                    })
                                }
                            });
                    }
                }
            }

            function getAll() {
                Promise.all([getJurusanSiswa(), getJurusan()])
                    .then(([jumlah, labels]) => {
                        // Menghitung total jumlah siswa dari semua jurusan
                        let total = jumlah.reduce((acc, count) => acc + count, 0);

                        // Mengubah label setiap jurusan menjadi format "nama jurusan (persentase)"
                        labels = labels.map((jurusan, index) => {
                            // Menghitung persentase siswa untuk setiap jurusan dengan 2 desimal
                            let percentage = ((jumlah[index] / total) * 100).toFixed(2);
                            //Logika Untuk Menghapus desimal dan ".00" jika persentase adalah bilangan bulat
                            if (percentage.endsWith('.00')) {
                                percentage = percentage.slice(0, -3);
                            }
                            // Agar Menampilkan Wujud Asli Dari Escaped Character
                            safe = jurusan.replace(/&lt;/g, "<").replace(/&gt;/g, ">");
                            // Menggabungkan nama jurusan dengan persentase untuk setiap label
                            return `${safe} ( ${percentage}% )`;
                            console.log(percentage)
                        });
                    
                        // console.log(labels)
                        // for (let i = 0; i < jurusan.length; i++) {

                        // }
                        // Menghasilkan warna latar belakang secara dinamis berdasarkan jumlah jurusan
                        const backgroundColor = generateRandomColors(labels.length);
                        // console.log(backgroundColor)
                        var data = {
                            // Menetapkan label untuk Pie CHart berdasarkan persentase siswa per jurusan
                            labels: labels,
                            datasets: [{
                                // Menggunakan jumlah siswa per jurusan sebagai data
                                data: jumlah,
                                backgroundColor: backgroundColor,
                            }]
                        };

                        var config = {
                            type: 'pie',
                            data: data,
                            options: {
                                responsive: false,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            color: 'white' // Mengubah warna teks legenda menjadi putih
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'Data Jurusan',
                                        fontSize: 24 
                                    }
                                }
                            }
                        };

                        // Merubah Canvas Dengan #jurusanChart Menggunakan Chart Js ( Pie )
                        var chartJurusan = new Chart(document.getElementById('jurusanChart'), config)
                    }).catch(error => {
                        // Menampilkan pesan kesalahan jika gagal mengambil data jurusan Menggunakan Swal.js
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal Mendapatkan Data Jurusan',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Menyembunyikan Element Agar Jika Tidak Bisa Mendpatkan data maka tidak terlihat kosong
                            document.getElementById('dataJurusan').style.display = 'none'
                        });
                    });
            }
            // Fungsi untuk menghasilkan warna latar belakang secara dinamis
            function generateRandomColors(numColors) {
                const colors = [];
                const colorSet = new Set(); // Membuat set untuk menyimpan warna yang sudah dihasilkan
                while (colors.length < numColors) {
                    // Generate random RGB values
                    const red = Math.floor(Math.random() * 150) + 100; // Nilai merah (red / r ) antara 100 dan 250
                    const green = Math.floor(Math.random() * 150) + 100; // Nilai hijau (green / g) antara 100 dan 250
                    const blue = Math.floor(Math.random() * 150) + 100; // Nilai biru (blue / b) antara 100 dan 250
                    // Combine RGB values into hexadecimal format
                    const randomColor = '#' + red.toString(16) + green.toString(16) + blue.toString(16);
                    // Menambahkan warna ke set jika warna tersebut belum ada
                    if (!colorSet.has(randomColor)) {
                        colors.push(randomColor);
                        colorSet.add(randomColor);
                    }
                }
                return colors;
            }


            // ketika Document Ready Maka akan langsung memanggil fungsi getStatus()
            getStatus();
            // Melakukan pemanggilan fungsi untuk mengambil data jumlah siswa per jurusan
            getAll();
            // getJurusan();
        </script>
</body>

</html>