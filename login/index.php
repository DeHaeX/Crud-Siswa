<?php
session_start();
if(isset($_SESSION['status'])){
    header('Location: /');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/bootstrap/icon/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container  d-flex flex-column h-100 w-100 justify-content-center align-items-center" style="height: 100vh !important;">
        <div class="card p-3 shadow-sm w-75">
            <div class="card-title text-center mt-2 pt-2">
                <h1>Login</h1>
            </div>
            <div class="card-body">
                <div>
                    <?= (isset($_GET['register'])) ? '<div class="alert alert-success">Register Success, Please Login First</div>' : ''?>
                    <input type="text" placeholder="Username" name="username" id="username" class="form-control p-2 mb-2">
                    <input type="password" placeholder="Password" id="password" name="password" class="form-control p-2 mb-2">
                    <button onclick="doLogin()" type="submit" class="btn btn-primary w-100 mt-3 p-2">
                        <div id="loginSpin" class="spinner-border small d-none text-light" role="status"><span class="visually-hidden">Loading...</span></div>
                        <span id="loginText">Login</span>
                    </button>
                    <p data-bs-toggle="modal" data-bs-target="#register" class="w-100 d-flex mt-3 justify-content-center text-primary" style="cursor: pointer;">Create New Account</p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="register" tabindex="-1" aria-labelledby="infoSiswaLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Register</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <!-- <div class="container d-flex justify-content-center align-items-center" style='height: 100vh;'> -->
        <div class="">
            <div class="p-3">

                <label for="lastName">Username : </label>
                <input type='text' name='userName' id='userName' oninput="(this.value == '') ? this.classList.add('is-invalid') : this.classList.remove('is-invalid'), this.classList.add('is-valid')"  value=''  class='form-control mb-1' placeholder='John.doe' required>
                <label for="passwd">Password : </label>
                <input type="password" oninput="validation(this.value);" name="pass" id="passwd" class="form-control me-3 mb-1" placeholder="8 - 12 Character" required maxlength="12">
                <div class="progress" role="progressbar" aria-valuenow="25">
                    <div id="progress" class="progress-bar py-2 bg-success"></div>
                </div>
                <!-- <ul style="list-style: none;"> -->
                <div class='text-danger' id="error1"><i class="bi bi-x-circle me-2"></i>Must include a uppercase character</div>
                <div class='text-danger' id='error2'><i class="bi bi-x-circle me-2"></i>Value length must be more than 8 character</div>
                <div class='text-danger' id="error3"><i class="bi bi-x-circle me-2"></i>Must include a symbol</div>
                <div class='text-danger' id='error4'><i class="bi bi-x-circle me-2"></i>Must include a number</div>
                <!-- </ul> -->
                <label for="validasi">Masukkan Kode Validasi :</label>
                <input type="text" placeholder="Kode Validasi..." class="form-class" id="validasi">
                <button type="submit" onclick="registerUser()" name='register' value="Register" id='register' class="btn btn-warning w-100 mt-4">Register</button>
          </div>
        </div>
      </div>
    </div>
<script src="../bootstrap/js/swal.js"></script>
<script src="../bootstrap//js/validation.js"></script>
<script src="../bootstrap/js/bootstrap.bundle.js"></script>
<script>
    let usernameInput = document.getElementById('username');
    let password = document.getElementById('password');
    let validasi = document.getElementById('validasi');
    let loginSpin = document.getElementById('loginSpin');
    let loginText = document.getElementById('loginText');
    let status = 0;
    function doLogin(){
        if(status == 0){
            status = 1;
            loginText.classList.toggle('d-none')
            loginSpin.classList.toggle('d-none')
            fetch('api/?username=' + usernameInput.value + '&password=' + password.value+"&validasi="+validasi.value)
            .then(response => response.json())
            .then(data => {
                if(data.status == 'success'){
                Swal.fire({
                  title: 'Login Berhasil',
                  text: data.text,
                  icon: data.status,
                  confirmButtonText: 'OK',
                  allowOutsideClick: false
                }).then((result) => {
                    (result.isConfirmed) ? window.location = "/" : window.location = "/"
                });
            }else{
                Swal.fire({
                  title: 'Login Gagal',
                  text: data.text,
                  icon: data.status,
                  confirmButtonText: 'OK',
                  allowOutsideClick: false
                }).then((result) => {
                    window.location = '/login'
                })
                status = 0;
                localStorage.removeItem('username')
                localStorage.removeItem('password')
                loginText.classList.toggle('d-none')
                loginSpin.classList.toggle('d-none')
                
            }
        })
        .catch(error => {
            
            Swal.fire({
              title: 'Login Gagal',
              text: error,
              icon: 'error',
              confirmButtonText: 'OK',
              allowOutsideClick: false
            })
            })
        }
        localStorage.setItem('username', usernameInput.value)
        localStorage.setItem('password', password.value)
    }
    function checkHasLogin(){
        if(localStorage['username'] && localStorage['password']){
            username.value = localStorage['username'];
            password.value = localStorage['password'];
        }
    }
    function registerUser() {
    var userName = document.getElementById('userName').value;
    var password = document.getElementById('passwd').value;


    // Buat permintaan GET ke api/register.php dengan menggunakan nilai dari input
    fetch('api/register.php?user=' + userName + '&pass=' + password)
        .then(response => response.json())
        .then(data => {
            console.log(data)
                Swal.fire({
                icon: data.status === 'success' ? 'success' : 'error',
                title: data.status === 'success' ? 'Success' : 'Oops...',
                text: data.text,
                allowOutsideClick: false
                }).then((result) => {
                    (result.isConfirmed) ? window.location = '/login?register=success' : window.location = '/login?register=success'
                });
        })
        .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                text: error.message
            });
                });
            }
    checkHasLogin();
</script>
</body>
</html>