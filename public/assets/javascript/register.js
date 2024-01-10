/*A REFACTORISER*/
document.addEventListener('DOMContentLoaded', function () {
    const rgxSpec = /[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/;
    const rgxNum = /[0-9]/;
    const rgxMaj = /[A-Z]/;
    const rgxEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const form = document.getElementById('register-form');

    form.addEventListener('submit', function (e) {
        console.log('test');
        e.preventDefault();
        const lastnameValue = document.getElementById('register-lastname').value.trim();
        const lastnameMessage = document.getElementById('lastnameMessage');

        if (lastnameValue.length < 2 || lastnameValue.length > 50) {
            lastnameMessage.innerHTML = '<small class="text-danger my-2">Le champs nom doit-être compris entre 2 et 50 caractères</small>';
        } else if (rgxNum.test(lastnameValue)) {
            lastnameMessage.innerHTML = '<small class="text-danger my-2">Le nom ne doit pas contenir de chiffres</small>';
        } else if (!isNaN(lastnameValue)) {
            lastnameMessage.innerHTML = '<small class="text-danger my-2">Le nom ne doit pas contenir de chiffres</small>';
        } else if (rgxSpec.test(lastnameValue)) {
            lastnameMessage.innerHTML = '<small class="text-danger my-2">Le nom ne doit pas contenir de caractères spéciaux</small>';
        } else {
            lastnameMessage.innerHTML = '';
        }
        const firstnameValue = document.getElementById('register-firstname').value.trim();
        const firstnameMessage = document.getElementById('firstnameMessage');

        if (firstnameValue.length < 2 || firstnameValue.length > 50) {
            firstnameMessage.innerHTML = '<small class="text-danger my-2">Le champs prénom doit-être compris entre 2 et 50 caractères</small>';
        } else if (rgxNum.test(firstnameValue)) {
            firstnameMessage.innerHTML = '<small class="text-danger my-2">Le prénom ne doit pas contenir de chiffres</small>';
        } else if (!isNaN(firstnameValue)) {
            firstnameMessage.innerHTML = '<small class="text-danger my-2">Le prénom ne doit pas contenir de chiffres</small>';
        } else if (rgxSpec.test(firstnameValue)) {
            firstnameMessage.innerHTML = '<small class="text-danger my-2">Le prénom ne doit pas contenir de caractères spéciaux</small>';
        } else {
            firstnameMessage.innerHTML = '';
        }

        const emailValue = document.getElementById('register-email').value.trim();
        const emailMessage = document.getElementById('emailMessage');

        if (!rgxEmail.test(emailValue)) {
            emailMessage.innerHTML = '<small class="text-danger my-2">Le format de l\'email est incorrect</small>';
        } else {
            emailMessage.innerHTML = '';
        }


        const passwordValue = document.getElementById('register-password').value.trim();
        const confirmPasswordValue = document.getElementById('register-c-password').value.trim();
        const passwordMessage = document.getElementById('passwordMessage');
        const confirmPasswordMessage = document.getElementById('c-passwordMessage');


        if (passwordValue.length < 8) {
            document.querySelector('#register-password').classList.add('border', 'border-danger');
            passwordMessage.innerHTML = '<small class="text-danger my-2">Le mot de passe doit contenir au moins 8 caratères</small>';
        } else if (!rgxSpec.test(passwordValue)) {
            passwordMessage.innerHTML = '<small class="text-danger my-2">Le mot de passe doit contenir au moins un caractère spécial: <b>!@#$%^&*()_+{}[]:;<b>,.?~-</b></small>';
             document.querySelector('#register-password').classList.add('border', 'border-danger');
        } else if (!rgxNum.test(passwordValue)) {
            passwordMessage.innerHTML = '<small class="text-danger my-2">Le mot de passe doit contenir au moins un chiffre</small>';
             document.querySelector('#register-password').classList.add('border', 'border-danger');
        } else if (!rgxMaj.test(passwordValue)) {
             document.querySelector('#register-password').classList.add('border', 'border-danger');
            passwordMessage.innerHTML = '<small class="text-danger my-2">Le mot de passe doit contenir au moins une majuscule</small>';
        } else {
            passwordMessage.innerHTML = '';
            document.querySelector('#register-password').classList.remove('border', 'border-danger');
        }

        if (passwordValue !== confirmPasswordValue) {
            confirmPasswordMessage.innerHTML = '<small class="text-danger mt-2">Les mots de passe ne correspondent pas</small>';
            document.querySelector('#register-password').classList.add('border', 'border-danger');
            document.querySelector('#register-c-password').classList.add('border', 'border-danger');
        } else {
            confirmPasswordMessage.innerHTML = '';
            document.querySelector('#register-password').classList.remove('border', 'border-danger');
            document.querySelector('#register-c-password').classList.remove('border', 'border-danger');
        }


       if (lastnameMessage.innerHTML === '' && firstnameMessage.innerHTML === '' && emailMessage.innerHTML === '' && passwordMessage.innerHTML === '' && confirmPasswordMessage.innerHTML === '') {
            alert('Votre compte a bien été créé');
           
            form.submit();
        }
    });

});
