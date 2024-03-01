function validation(value){
    let progress = document.getElementById('progress');
    let strength = 0;
    let error1 = document.getElementById('error1');
    let error2 = document.getElementById('error2');
    let error3 = document.getElementById('error3');
    let error4 = document.getElementById('error4');

    let symbol = [
        '<i class="bi bi-x-circle me-2"></i>',
        '<i class="bi bi-check-circle me-2"></i>'
    ]
    if(value.length < 11){
        value.length = 12;
    }
    if(value.length >= 8) { 
        strength += 25 
        error2.classList.add('text-success')
        error2.classList.remove('text-danger')
        error2.innerHTML = symbol[1] + 'Value length must be more than 8 character'
    }else{
        error2.classList.remove('text-success')
        error2.classList.add('text-danger')
        error2.innerHTML = symbol[0] + 'Value length must be more than 8 character'
    }
    if(/[!@#$%^&*(),.?":{}|<>]/.test(value)) {
        strength += 25  
        error3.classList.add('text-success')
        error3.classList.remove('text-danger')
        error3.innerHTML = symbol[1] + 'Must include a symbol'
    } else{
        error3.classList.remove('text-success')
        error3.classList.add('text-danger')
        error3.innerHTML = symbol[0] + 'Must include a symbol'
        
    }
    if(/[A-Z]/.test(value)){
        strength += 25
        error1.classList.add('text-success')
        error1.classList.remove('text-danger')
        error1.innerHTML = symbol[1] + 'Must include a uppercase character'
    }else{
        error1.classList.remove('text-success')
        error1.classList.add('text-danger')
        error1.innerHTML = symbol[0] + 'Must include a uppercase character'
    }
    if(/\d/.test(value)) 
    { 
        error4.classList.add('text-success')
        error4.classList.remove('text-danger')
        error4.innerHTML = symbol[1] + 'Must include a number'
        strength += 25 
    }
    else{
        error4.classList.remove('text-success')
        error4.classList.add('text-danger')
        error4.innerHTML = symbol[0] + 'Must include a number'
    }

    progress.style.width = strength + '%';

    if(progress.style.width == '25%' || progress.style.width == '50%'){
        progress.classList.add('bg-danger');
        progress.classList.remove('bg-success')
        progress.innerHTML = 'Terlalu Mudah';
    }
    if(progress.style.width == '75%'){
        progress.classList.add('bg-warning');
        progress.classList.remove('bg-danger')
        progress.innerHTML = 'Rumit';
    }
    if(progress.style.width == '100%'){
        progress.classList.add('bg-success');
        progress.classList.remove('bg-warning');
        progress.innerHTML = 'Sangat Rumit';
        document.getElementById('passwd').classList.remove('is-invalid');
        document.getElementById('passwd').classList.add('is-valid');
    }else{
        document.getElementById('passwd').classList.remove('is-valid');
        document.getElementById('passwd').classList.add('is-invalid');
    }
}
